#!/usr/bin/env bash
# Only run against the disposable Joomla CI instance.
set -euo pipefail
: "${JOOMLA_ROOT:?}"
root="$(pwd)"
version="$(python3 -c "import xml.etree.ElementTree as E;print(E.parse('templateDetails.xml').getroot().findtext('version'))")"
db(){ mysql --protocol=TCP -h 127.0.0.1 -u joomla_test -N -B joomla_test -e "$1"; }
install(){ (cd "$JOOMLA_ROOT" && php cli/joomla.php extension:install --no-interaction --path="$1"); }
styles(){ db "SELECT id,title,home,params FROM ci_template_styles WHERE template='operaplus' AND client_id=0 ORDER BY id"; }
magazine(){ db "SELECT id FROM ci_template_styles WHERE template='operaplus' AND client_id=0 AND JSON_UNQUOTE(JSON_EXTRACT(params,'$.presentationMode'))='magazine'"; }
global_defaults(){ db "SELECT id,home FROM ci_template_styles WHERE home<>'0' ORDER BY id"; }
defaults="$(global_defaults)"
install "$root/dist/tpl_operaplus-$version.zip"
test ! -e "$JOOMLA_ROOT/templates/operaplus/tools/render_article_layouts.php"
test -f "$JOOMLA_ROOT/media/templates/site/operaplus/images/template_preview.png"
test -f "$JOOMLA_ROOT/media/templates/site/operaplus/images/template_thumbnail.png"
test "$(db "SELECT JSON_UNQUOTE(JSON_EXTRACT(manifest_cache,'$.creationDate')) FROM ci_extensions WHERE element='operaplus' AND type='template'")" = "$(cat RELEASE_DATE)"
test -f "$JOOMLA_ROOT/templates/operaplus/field/opstylemode.php"
test "$(styles | wc -l)" = 2
[[ "$(magazine)" =~ ^[0-9]+$ ]]
test "$(global_defaults)" = "$defaults"
test "$(db "SELECT home FROM ci_template_styles WHERE id=$(magazine)")" = 0
before="$(styles)"
install "$root/dist/tpl_operaplus-$version.zip"
test "$(styles)" = "$before"
id="$(db "SELECT extension_id FROM ci_extensions WHERE element='operaplus' AND type='template' AND client_id=0")"
(cd "$JOOMLA_ROOT" && php cli/joomla.php extension:remove "$id" --no-interaction)
test -z "$(styles)"
echo 'Fresh installation and repeat installation preserve Joomla defaults and create exactly two styles.'
install "$RUNNER_TEMP/baseline/dist/tpl_operaplus-1.21.0.zip"
standard="$(db "SELECT id FROM ci_template_styles WHERE template='operaplus' AND client_id=0")"
[[ "$standard" =~ ^[0-9]+$ ]]
db "UPDATE ci_template_styles SET title='CI existing institutional style',params=JSON_OBJECT('brandText','CI existing branding','pageTheme','dark') WHERE id=$standard; UPDATE ci_menu SET template_style_id=$standard WHERE client_id=0 AND home=1;"
before="$(styles)"
menus="$(db "SELECT id,template_style_id FROM ci_menu ORDER BY id")"
install "$root/dist/tpl_operaplus-$version.zip"
test "$(styles | wc -l)" = 2
test "$(db "SELECT id,title,home,params FROM ci_template_styles WHERE id=$standard")" = "$before"
test "$(db "SELECT id,template_style_id FROM ci_menu ORDER BY id")" = "$menus"
mid="$(magazine)"
[[ "$mid" =~ ^[0-9]+$ ]]
test "$(db "SELECT JSON_UNQUOTE(JSON_EXTRACT(params,'$.brandText')) FROM ci_template_styles WHERE id=$mid")" = 'CI existing branding'
test "$(db "SELECT JSON_UNQUOTE(JSON_EXTRACT(params,'$.pageTheme')) FROM ci_template_styles WHERE id=$mid")" = light
db "UPDATE ci_template_styles SET title='CI renamed magazine',params=JSON_SET(params,'$.magazineTitle','Existing custom title') WHERE id=$mid;"
before="$(styles)"
install "$root/dist/tpl_operaplus-$version.zip"
test "$(styles)" = "$before"
test "$(magazine)" = "$mid"
test "$(global_defaults)" = "$defaults"
test "$(db "SELECT id,template_style_id FROM ci_menu ORDER BY id")" = "$menus"
echo 'Upgrade preserves institutional settings/menu IDs and an existing customized Magazine style; no duplicates.'

# Upgrade from the latest 1.x preview with an existing customized Magazine.
id="$(db "SELECT extension_id FROM ci_extensions WHERE element='operaplus' AND type='template' AND client_id=0")"
db "UPDATE ci_menu SET template_style_id=0 WHERE client_id=0 AND template_style_id IN (SELECT id FROM ci_template_styles WHERE template='operaplus')"
(cd "$JOOMLA_ROOT" && php cli/joomla.php extension:remove "$id" --no-interaction)
install "$RUNNER_TEMP/preview/dist/tpl_operaplus-1.29.0.zip"
mid="$(magazine)"
standard="$(db "SELECT id FROM ci_template_styles WHERE template='operaplus' AND id<>$mid LIMIT 1")"
db "UPDATE ci_template_styles SET title='CI custom Magazine RC',params=JSON_SET(params,'$.magazineTitle','Keep custom title') WHERE id=$mid; UPDATE ci_menu SET template_style_id=$standard WHERE client_id=0 AND home=1;"
menus="$(db "SELECT id,template_style_id FROM ci_menu ORDER BY id")"
test -f "$JOOMLA_ROOT/templates/operaplus/tools/render_article_layouts.php"
printf 'Keep site-owned files\n' > "$JOOMLA_ROOT/templates/operaplus/tools/site-owned.txt"
before="$(styles)"
install "$root/dist/tpl_operaplus-$version.zip"
test "$(styles)" = "$before"
test "$(db "SELECT id,template_style_id FROM ci_menu ORDER BY id")" = "$menus"
test ! -e "$JOOMLA_ROOT/templates/operaplus/tools/render_article_layouts.php"
test ! -e "$JOOMLA_ROOT/templates/operaplus/tools/test_seo.php"
test -f "$JOOMLA_ROOT/templates/operaplus/tools/site-owned.txt"
test "$(db "SELECT COUNT(*) FROM ci_update_sites s JOIN ci_update_sites_extensions se ON s.update_site_id=se.update_site_id JOIN ci_extensions e ON e.extension_id=se.extension_id WHERE e.element='operaplus' AND e.type='template' AND s.location='https://raw.githubusercontent.com/jeckodevelopment/tpl_operaplus/main/updates/stable.xml'")" = 1
echo 'Latest preview upgrade, legacy test cleanup, custom files and stable-only update site passed.'

# Exercise the real Joomla form field filter, not the submitted hidden value.
php "$root/tools/test_style_mode.php" "$JOOMLA_ROOT" "$standard" "$mid"

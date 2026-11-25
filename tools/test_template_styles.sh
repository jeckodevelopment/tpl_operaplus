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

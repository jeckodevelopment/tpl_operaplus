#!/usr/bin/env bash
# Run exclusively on the disposable Joomla CI site after template lifecycle tests.
set -euo pipefail
: "${JOOMLA_ROOT:?}"
root="$(pwd)"
db(){ mysql --protocol=TCP -h 127.0.0.1 -u joomla_test -N -B joomla_test -e "$1"; }
(cd .ci-extensions && python3 tools/package.py)
(cd "$JOOMLA_ROOT" && php cli/joomla.php extension:install --no-interaction --path="$root/.ci-extensions/dist/pkg_operaplus-1.0.0.zip")
mid="$(db "SELECT id FROM ci_template_styles WHERE template='operaplus' AND JSON_UNQUOTE(JSON_EXTRACT(params,'$.presentationMode'))='magazine'")"
[[ "$mid" =~ ^[0-9]+$ ]]
# Force a regular Magazine page even when using the native featured home route.
db "UPDATE ci_template_styles SET params=JSON_SET(params,'$.magazineHomeMenu',999999) WHERE id=$mid; UPDATE ci_menu SET template_style_id=$mid WHERE client_id=0 AND home=1; UPDATE ci_modules SET published=0 WHERE client_id=0;"
db "INSERT INTO ci_modules (title,position,published,module,access,showtitle,params,client_id,language) VALUES ('CI Atlas hidden title','magazine-sidebar',1,'mod_operaplus_atlas',1,1,'{\"mode\":\"context\",\"hide_empty\":1,\"cache\":0}',0,'*'); INSERT INTO ci_modules_menu (moduleid,menuid) VALUES (LAST_INSERT_ID(),0);"
module="$(db "SELECT id FROM ci_modules WHERE title='CI Atlas hidden title'")"
cache="$(php -r 'define("_JEXEC",1); define("JPATH_BASE",$argv[1]."/administrator"); require JPATH_BASE."/includes/defines.php"; echo JPATH_CACHE."/operaplus-atlas-v1.json";' "$JOOMLA_ROOT")"
mkdir -p "$(dirname "$cache")"
cat > "$cache" <<'JSON'
{"schema_version":1,"source_revision":"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa","entities":[{"type":"opera","id":"ci-opera","title":"CI Atlas opera","url":"https://opera.plus/atlas/opere/ci-opera/"}]}
JSON
(cd "$JOOMLA_ROOT" && php -S 127.0.0.1:8080 -t "$JOOMLA_ROOT") > "$RUNNER_TEMP/editorial-web.log" 2>&1 &
server=$!
trap 'kill "$server" 2>/dev/null || true; tail -n 30 "$RUNNER_TEMP/editorial-web.log"' EXIT
url=http://127.0.0.1:8080
for i in $(seq 1 20); do
  if curl -fsS "$url/index.php" > "$RUNNER_TEMP/editorial.html"; then break; fi
  sleep 1
done
page="$RUNNER_TEMP/editorial.html"
visit(){ curl -fsS "$url/index.php" > "$page"; grep -q 'op-magazine' "$page"; }
contains(){ grep -Fq "$1" "$page" || { echo "Missing: $1"; exit 1; }; }
absent(){ if grep -Fq "$1" "$page"; then echo "Unexpected: $1"; exit 1; fi; }
visit
contains 'op-mag-row--full'
absent 'op-mag-row__aside'
absent 'CI Atlas hidden title'
absent 'op-atlas-module'
# The same empty module in the ordinary right position must also collapse.
db "UPDATE ci_modules SET position='sidebar-right' WHERE id=$module;"
visit
absent 'op-mag-row__aside'
absent 'CI Atlas hidden title'
# Explicit empty state remains visible when hiding is disabled.
db "UPDATE ci_modules SET params=JSON_OBJECT('mode','context','hide_empty',0,'cache',0) WHERE id=$module;"
visit
contains 'op-mag-row__aside'
contains 'op-atlas-empty'
contains 'CI Atlas hidden title'
# Manual references work independently of article context.
db "UPDATE ci_modules SET params=JSON_OBJECT('mode','manual','references','opera:ci-opera','hide_empty',1,'cache',0) WHERE id=$module;"
visit
contains 'CI Atlas opera'
contains 'op-atlas-link'
# Unresolved manual references collapse too.
db "UPDATE ci_modules SET params=JSON_OBJECT('mode','manual','references','opera:missing','hide_empty',1,'cache',0) WHERE id=$module;"
visit
absent 'op-mag-row__aside'
# A visible CTA must keep the sidebar open while the unrelated Atlas is hidden.
for variant in light dark horizontal; do
  db "INSERT INTO ci_modules (title,position,published,module,access,showtitle,params,client_id,language) VALUES ('CI CTA $variant','magazine-sidebar',1,'mod_operaplus_cta',1,0,JSON_OBJECT('variant','$variant','heading','CI CTA $variant','description','Editorial text','icon','envelope','watermark',1,'button_text','Subscribe','button_url','/newsletter','cache',0),0,'*'); INSERT INTO ci_modules_menu (moduleid,menuid) VALUES (LAST_INSERT_ID(),0);"
done
visit
contains 'op-mag-row__aside'
absent 'CI Atlas hidden title'
absent 'op-atlas-module'
for variant in light dark horizontal; do contains "op-cta--$variant"; done
contains 'op-cta__watermark'
contains 'href="/newsletter"'
for css in editor.css editor.min.css; do
  curl -fsS "$url/media/templates/site/operaplus/css/$css" | grep -Fq 'p.op-interview-question'
done
echo 'Native Magazine integration: hidden chrome, empty and manual Atlas, mixed sidebar, CTA variants and editor CSS passed.'

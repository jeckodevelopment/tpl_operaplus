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

# Real Joomla head output and asset isolation.
db "UPDATE ci_template_styles SET params=JSON_SET(params,'$.defaultOgImage','images/fallback.jpg','$.externalFont','auto') WHERE id=$mid;"
curl -fsS "$url/index.php?utm_source=ci" > "$page"
assert_head(){
  python3 - "$page" "$1" "$2" <<'PY'
from html.parser import HTMLParser
import sys
class Head(HTMLParser):
    def __init__(self):
        super().__init__(); self.meta={}; self.canonical=[]
    def handle_starttag(self,tag,attrs):
        a=dict(attrs)
        if tag=='meta' and 'property' in a:
            self.meta.setdefault(a['property'],[]).append(a.get('content',''))
        if tag=='link' and a.get('rel')=='canonical':
            self.canonical.append(a.get('href'))
h=Head(); h.feed(open(sys.argv[1]).read())
assert len(h.canonical)==1 and 'utm_source' not in h.canonical[0],h.canonical
for key in ('og:title','og:type','og:site_name','og:url','og:image'):
    assert len(h.meta.get(key,[]))==1 and h.meta[key][0],(key,h.meta)
assert h.meta['og:type']==[sys.argv[2]],h.meta
assert h.meta['og:image'][0].endswith(sys.argv[3]),h.meta
if sys.argv[2]=='article':
    assert h.meta['og:description']==['CI editorial description'],h.meta
PY
}
assert_head website /images/fallback.jpg
absent 'fonts.googleapis.com'
contains 'magazine.css'
# Build a public article in the disposable database.
catid="$(db "SELECT id FROM ci_categories WHERE extension='com_content' AND published=1 AND access=1 ORDER BY id LIMIT 1")"
author="$(db "SELECT id FROM ci_users ORDER BY id LIMIT 1")"
[[ "$catid" =~ ^[0-9]+$ && "$author" =~ ^[0-9]+$ ]]
db "INSERT INTO ci_content (title,alias,introtext,\`fulltext\`,state,catid,created,created_by,modified,publish_up,images,urls,attribs,metadesc,access,metadata,language) VALUES ('CI SEO article','ci-seo-article','<p>CI public body</p>','',1,$catid,UTC_TIMESTAMP(),$author,UTC_TIMESTAMP(),UTC_TIMESTAMP(),JSON_OBJECT('image_fulltext','images/article.jpg','image_intro','images/intro.jpg','image_fulltext_alt','CI portrait'),'{}','{}','CI editorial description',1,'{}','*');"
article="$(db "SELECT id FROM ci_content WHERE alias='ci-seo-article'")"
home="$(db "SELECT id FROM ci_menu WHERE client_id=0 AND home=1 LIMIT 1")"
curl -fsS "$url/index.php?option=com_content&view=article&id=$article&Itemid=$home&utm_source=ci" > "$page"
contains 'CI public body'
assert_head article /images/article.jpg

# BUG-008: native Articles modules in both related layouts, using the inherited
# landing Itemid and two public articles in the same category.
db "INSERT INTO ci_content (title,alias,introtext,\`fulltext\`,state,catid,created,created_by,modified,publish_up,images,urls,attribs,metadesc,access,metadata,language) VALUES ('CI related sibling','ci-related-sibling','<p>CI related intro</p>','',1,$catid,UTC_TIMESTAMP(),$author,UTC_TIMESTAMP(),UTC_TIMESTAMP(),JSON_OBJECT('image_intro','images/related.jpg'),'{}','{}','',1,'{}','*');"
db "UPDATE ci_template_styles SET params=JSON_SET(params,'$.magazineHomeMenu',$home,'$.magazineShowComponent',0) WHERE id=$mid;"
db "INSERT INTO ci_modules (title,position,published,module,access,showtitle,content,params,client_id,language) VALUES ('CI landing marker','magazine-cover',1,'mod_custom',1,0,'<p>CI landing marker</p>','{}',0,'*'); INSERT INTO ci_modules_menu (moduleid,menuid) VALUES (LAST_INSERT_ID(),$home);"
for layout in related-sidebar related-compact related-horizontal; do
  pos=magazine-sidebar
  if [[ "$layout" == related-horizontal ]]; then pos=main-bottom; fi
  db "INSERT INTO ci_modules (title,position,published,module,access,showtitle,params,client_id,language) VALUES ('CI $layout','$pos',1,'mod_articles',1,1,JSON_OBJECT('layout','operaplus:magazine-$layout','mode','normal','catid',JSON_ARRAY($catid),'count',3,'category_filtering_type',1,'exclude_current',1,'item_title',1,'link_titles',1,'img_intro_full','intro','cache',0),0,'*'); INSERT INTO ci_modules_menu (moduleid,menuid) VALUES (LAST_INSERT_ID(),$home);"
done
curl -fsS "$url/index.php?option=com_content&view=article&id=$article&Itemid=$home" > "$page"
contains 'CI public body'
contains 'op-mag-row__aside'
contains 'CI related sibling'
for layout in related-sidebar related-compact related-horizontal; do contains "op-mag-articles--$layout"; done
absent 'CI landing marker'
# Native dynamic mode must work too on the article route; exclusion stays enabled.
db "UPDATE ci_modules SET params=JSON_SET(params,'$.mode','dynamic','$.show_on_article_page',1) WHERE title IN ('CI related-sidebar','CI related-compact');"
curl -fsS "$url/index.php?option=com_content&view=article&id=$article&Itemid=$home" > "$page"
contains 'op-mag-articles--related-sidebar'
contains 'op-mag-articles--related-compact'
# On the landing, the component/sidebar may be intentionally hidden.
curl -fsSL "$url/index.php?Itemid=$home" > "$page"
contains 'CI landing marker'
absent 'op-mag-articles--related-sidebar'
absent 'op-mag-articles--related-compact'

# RC5 category introduction, editorial metadata and native pagination.
# Use native non-SEF URLs on the disposable PHP server (no rewrite rules).
sef_enabled="$(db "SELECT enabled FROM ci_extensions WHERE type='plugin' AND folder='system' AND element='sef'")"
db "UPDATE ci_extensions SET enabled=0 WHERE type='plugin' AND folder='system' AND element='sef';"
# Use a real non-home category menu item.
db "INSERT INTO ci_menu (menutype,title,alias,path,link,type,published,parent_id,level,component_id,access,img,params,lft,rgt,home,language,client_id,template_style_id) SELECT menutype,'CI category','ci-category','ci-category',CONCAT('index.php?option=com_content&view=category&layout=magazine-grid&id=',$catid),'component',1,1,1,component_id,1,'',params,1000,1001,0,'*',0,$mid FROM ci_menu WHERE id=$home;"
category_menu="$(db "SELECT id FROM ci_menu WHERE alias='ci-category' AND client_id=0")"
for layout in grid list dossier; do
  db "UPDATE ci_menu SET link='index.php?option=com_content&view=category&layout=magazine-$layout&id=$catid',params=JSON_SET(params,'$.show_intro',0,'$.show_author',1,'$.show_category',1,'$.show_publish_date',1,'$.num_leading_articles',0,'$.num_intro_articles',1,'$.num_links',0,'$.show_pagination',1) WHERE id=$category_menu;"
  curl -fsSL "$url/index.php?option=com_content&view=category&layout=magazine-$layout&id=$catid&Itemid=$category_menu" > "$page"
  contains "op-mag-category--$layout"
  contains 'op-mag-category-info'
  absent 'op-mag-category-intro'
  contains 'pagination__wrapper'
  contains 'icon-angle-double-left'
  contains 'icon-angle-double-right'
  db "UPDATE ci_menu SET params=JSON_SET(params,'$.show_intro',1) WHERE id=$category_menu;"
  curl -fsSL "$url/index.php?option=com_content&view=category&layout=magazine-$layout&id=$catid&Itemid=$category_menu" > "$page"
  contains 'op-mag-category-intro'
done
db "DELETE FROM ci_menu WHERE id=$category_menu; UPDATE ci_extensions SET enabled=$sef_enabled WHERE type='plugin' AND folder='system' AND element='sef';"
echo 'Magazine category intro toggle, metadata and pagination passed.'
db "DELETE FROM ci_modules_menu WHERE moduleid IN (SELECT id FROM ci_modules WHERE title IN ('CI landing marker','CI related-sidebar','CI related-compact','CI related-horizontal')); DELETE FROM ci_modules WHERE title IN ('CI landing marker','CI related-sidebar','CI related-compact','CI related-horizontal'); DELETE FROM ci_content WHERE alias='ci-related-sibling'; UPDATE ci_template_styles SET params=JSON_SET(params,'$.magazineHomeMenu',999999) WHERE id=$mid;"
echo 'Native related article layouts, inherited menu assignment and landing suppression passed.'

standard="$(db "SELECT id FROM ci_template_styles WHERE template='operaplus' AND id<>$mid LIMIT 1")"
db "UPDATE ci_menu SET template_style_id=$standard WHERE client_id=0 AND home=1;"
curl -fsS "$url/index.php" > "$page"
absent 'magazine.css'
echo 'Native SEO head, article image priority, canonical, font and asset isolation passed.'

# Exercise style options through the actual installed template and test inherited home Itemid.
db "UPDATE ci_menu SET template_style_id=$mid WHERE client_id=0 AND home=1; UPDATE ci_template_styles SET params=JSON_SET(params,'$.magazineImageRatio','wide','$.magazineOverlay','strong','$.magazineSeparators',0) WHERE id=$mid;"
curl -fsS "$url/index.php" > "$page"
contains 'op-mag-ratio-wide op-mag-overlay-strong op-mag-no-separators'
db "UPDATE ci_template_styles SET params=JSON_SET(params,'$.magazineImageRatio','invalid','$.magazineOverlay','invalid') WHERE id=$mid;"
curl -fsS "$url/index.php" > "$page"
contains 'op-mag-ratio-classic op-mag-overlay-classic'
db "UPDATE ci_menu SET template_style_id=$standard WHERE client_id=0 AND home=1;"
curl -fsS "$url/index.php?option=com_content&view=article&id=$article&Itemid=$home" > "$page"
contains 'CI public body'
absent 'op-mag-ratio-'
assert_head article /images/article.jpg
# Courses use their existing custom layout only for authorized viewers.
db "UPDATE ci_categories SET alias='corsi' WHERE id=$catid; UPDATE ci_content SET \`fulltext\`='<p>CI PRIVATE FULLTEXT MARKER</p>',attribs=JSON_OBJECT('show_noauth',1,'show_readmore',1) WHERE id=$article;"
curl -fsS "$url/index.php?option=com_content&view=article&id=$article&Itemid=$home" > "$page"
contains 'op-course-detail'
contains 'CI PRIVATE FULLTEXT MARKER'
db "UPDATE ci_content SET access=2 WHERE id=$article;"
curl -sS -L "$url/index.php?option=com_content&view=article&id=$article&Itemid=$home" > "$page"
absent 'CI PRIVATE FULLTEXT MARKER'
absent 'og:image" content="http://127.0.0.1:8080/images/article.jpg'
echo 'Native style options, inherited Itemid and course ACL regression passed.'

# Standard shell: verify optional modules and the native category fallback on the disposable site.
db "UPDATE ci_content SET access=1 WHERE id=$article; UPDATE ci_template_styles SET params=JSON_SET(params,'$.showHomeComponent',1,'$.externalFont','system') WHERE id=$standard;"
for position in hero sidebar-left sidebar-right; do
  db "INSERT INTO ci_modules (title,position,published,module,access,showtitle,content,params,client_id,language) VALUES ('CI Standard $position','$position',1,'mod_custom',1,0,'<p>CI Standard $position marker</p>','{}',0,'*'); INSERT INTO ci_modules_menu (moduleid,menuid) VALUES (LAST_INSERT_ID(),0);"
done
curl -fsS "$url/index.php" > "$page"
contains 'CI Standard hero marker'
contains 'CI Standard sidebar-left marker'
contains 'CI Standard sidebar-right marker'
contains 'has-both-sidebars'
absent 'magazine.css'
absent 'fonts.googleapis.com'
curl -fsS "$url/index.php?option=com_content&view=article&id=$article&Itemid=$home" > "$page"
contains 'op-course-detail'
absent 'CI Standard hero marker'
contains 'CI Standard sidebar-left marker'
contains 'CI Standard sidebar-right marker'
# Category retains course rendering when public; unsafe image is discarded.
db "UPDATE ci_content SET images=JSON_OBJECT('image_intro','javascript:alert(1)') WHERE id=$article;"
curl -fsS "$url/index.php?option=com_content&view=category&layout=blog&id=$catid&Itemid=$home" > "$page"
contains 'op-course-index'
contains 'CI SEO article'
absent 'javascript:alert(1)'
# Core category handles unauthorized articles, including login routing and teaser settings.
db "UPDATE ci_content SET access=2 WHERE id=$article;"
curl -fsS "$url/index.php?option=com_content&view=category&layout=blog&id=$catid&Itemid=$home" > "$page"
absent 'CI PRIVATE FULLTEXT MARKER'
echo 'Native Standard home, sidebars, inherited Itemid, course catalog image and ACL regression passed.'

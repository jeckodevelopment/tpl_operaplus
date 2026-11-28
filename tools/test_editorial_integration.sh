#!/usr/bin/env bash
# Run exclusively on the disposable Joomla CI site after template lifecycle tests.
set -euo pipefail
: "${JOOMLA_ROOT:?}"
root="$(pwd)"
db(){ mysql --protocol=TCP -h 127.0.0.1 -u joomla_test -N -B joomla_test -e "$1"; }
(cd .ci-extensions && python3 tools/package.py)
extensions_version="$(cat .ci-extensions/VERSION)"
(cd "$JOOMLA_ROOT" && php cli/joomla.php extension:install --no-interaction --path="$root/.ci-extensions/dist/pkg_operaplus-$extensions_version.zip")
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

# RC7: real native contact menu, associated user, author identity and ACL.
contactcat="$(db "SELECT id FROM ci_categories WHERE extension='com_contact' AND published=1 AND access=1 LIMIT 1")"
contactcomponent="$(db "SELECT extension_id FROM ci_extensions WHERE type='component' AND element='com_contact'")"
db "INSERT INTO ci_contact_details (name,alias,con_position,misc,email_to,published,params,user_id,catid,access,language,created,modified,metadesc,metadata) VALUES ('CI author profile','ci-author-profile','CI editor','CI public biography','ci-private@example.invalid',1,JSON_OBJECT('show_articles',0),$author,$contactcat,1,'*',UTC_TIMESTAMP(),UTC_TIMESTAMP(),'','{}');"
contact="$(db "SELECT id FROM ci_contact_details WHERE alias='ci-author-profile'")"
db "INSERT INTO ci_menu (menutype,title,alias,path,link,type,published,parent_id,level,component_id,access,img,params,lft,rgt,home,language,client_id,template_style_id) SELECT menutype,'CI author','ci-author','ci-author',CONCAT('index.php?option=com_contact&view=contact&layout=magazine-author&id=',$contact),'component',1,1,1,$contactcomponent,1,'',JSON_OBJECT('show_articles',1,'articles_display_num',5,'show_misc',1,'show_position',1,'show_email',1),1002,1003,0,'*',0,$mid FROM ci_menu WHERE id=$home;"
authormenu="$(db "SELECT id FROM ci_menu WHERE alias='ci-author' AND client_id=0")"
# Two existing published articles share the author. Add inaccessible/future/other-ID fixtures.
for kind in private future expired unpublished foreign; do
  state=1; access=1; creator="$author"; up="UTC_TIMESTAMP()"; down=NULL
  case "$kind" in
    private) access=2;;
    future) up="DATE_ADD(UTC_TIMESTAMP(), INTERVAL 2 DAY)";;
    expired) down="DATE_SUB(UTC_TIMESTAMP(), INTERVAL 2 DAY)";;
    unpublished) state=0;;
    foreign) creator=0;;
  esac
  db "INSERT INTO ci_content (title,alias,introtext,\`fulltext\`,state,catid,created,created_by,created_by_alias,modified,publish_up,publish_down,images,urls,attribs,metadesc,access,metadata,language) VALUES ('CI excluded $kind','ci-author-$kind','CI excluded body','',$state,$catid,UTC_TIMESTAMP(),$creator,'CI author profile',UTC_TIMESTAMP(),$up,$down,'{}','{}','{}','',$access,'{}','*');"
done
authorurl="$url/index.php?option=com_contact&view=contact&layout=magazine-author&id=$contact&Itemid=$authormenu"
curl -fsSL "$authorurl" > "$page"
contains 'op-mag-author__articles'
contains 'op-mag-author__article"'
contains 'CI public biography'
contains 'CI SEO article'
contains 'CI related sibling'
absent 'ci-private@example.invalid'
for kind in private future expired unpublished foreign; do absent "CI excluded $kind"; done
# Link must contain the actual article ID, not an empty slug.
python3 - "$page" "$article" <<'PY'
from html.parser import HTMLParser
from urllib.parse import urlparse, parse_qs
import sys
class Links(HTMLParser):
    def __init__(self):
        super().__init__(); self.href=None; self.matches=[]
    def handle_starttag(self, tag, attrs):
        if tag=='a': self.href=dict(attrs).get('href')
    def handle_data(self, data):
        if data.strip()=='CI SEO article' and self.href: self.matches.append(self.href)
    def handle_endtag(self, tag):
        if tag=='a': self.href=None
p=Links(); p.feed(open(sys.argv[1]).read())
assert any(parse_qs(urlparse(link).query).get('id',[''])[0].split(':')[0]==sys.argv[2] or 'ci-seo-article' in link for link in p.matches),p.matches
PY
db "UPDATE ci_menu SET params=JSON_SET(params,'$.show_articles',0) WHERE id=$authormenu;"
curl -fsSL "$authorurl" > "$page"
absent 'op-mag-author__articles'
db "UPDATE ci_menu SET params=JSON_SET(params,'$.show_articles',1) WHERE id=$authormenu; UPDATE ci_contact_details SET user_id=0 WHERE id=$contact;"
curl -fsSL "$authorurl" > "$page"
contains 'op-mag-author__articles'
absent 'op-mag-author__article"'
db "DELETE FROM ci_menu WHERE id=$authormenu; DELETE FROM ci_contact_details WHERE id=$contact; DELETE FROM ci_content WHERE alias IN ('ci-author-private','ci-author-future','ci-author-expired','ci-author-unpublished','ci-author-foreign');"
echo 'Native Magazine author: explicit menu choice, linked identity, publication and ACL, article route and empty states passed.'

# RC9: real Joomla review fields, image and body ordering.
db "INSERT INTO ci_fields (context,title,name,label,type,description,state,params,fieldparams,language,created_time,modified_time,access) VALUES ('com_content.article','CI performance theatre','review-theatre','Theatre','text','',1,JSON_OBJECT('display',0),'{}','*',UTC_TIMESTAMP(),UTC_TIMESTAMP(),1);"
reviewfield="$(db "SELECT id FROM ci_fields WHERE name='review-theatre' AND context='com_content.article'")"
db "INSERT INTO ci_fields_categories (field_id,category_id) VALUES ($reviewfield,$catid); INSERT INTO ci_fields_values (field_id,item_id,value) VALUES ($reviewfield,'$article','CI performance venue');"
db "UPDATE ci_menu SET link='index.php?option=com_content&view=article&layout=magazine-review&id=$article',params=JSON_SET(params,'$.show_title',1) WHERE id=$category_menu;"
reviewurl="$url/index.php?option=com_content&view=article&layout=magazine-review&id=$article&Itemid=$category_menu"
curl -fsSL "$reviewurl" > "$page"
contains 'op-mag-article--review'
contains 'CI performance venue'
python3 - "$page" <<'PY'
import sys
from html.parser import HTMLParser
class Order(HTMLParser):
    def __init__(self):
        super().__init__(); self.order=[]
    def handle_starttag(self, tag, attrs):
        a=dict(attrs)
        if tag=='img' and 'images/article.jpg' in a.get('src',''): self.order.append('image')
        if tag=='section' and 'op-mag-review' in a.get('class','').split(): self.order.append('panel')
    def handle_data(self, data):
        if 'CI public body' in data: self.order.append('body')
p=Order(); p.feed(open(sys.argv[1]).read())
assert p.order.count('panel')==1 and 'image' in p.order and 'body' in p.order and p.order.index('image')<p.order.index('body')<p.order.index('panel'),p.order
PY
# Without a full image the panel still follows the text, and empty values omit it.
db "UPDATE ci_content SET images=JSON_REMOVE(images,'$.image_fulltext') WHERE id=$article;"
curl -fsSL "$reviewurl" > "$page"
contains 'CI performance venue'
contains 'CI public body'
db "DELETE FROM ci_fields_values WHERE field_id=$reviewfield; DELETE FROM ci_fields_categories WHERE field_id=$reviewfield; DELETE FROM ci_fields WHERE id=$reviewfield; UPDATE ci_content SET images=JSON_SET(images,'$.image_fulltext','images/article.jpg') WHERE id=$article;"
curl -fsSL "$reviewurl" > "$page"
absent 'class="op-mag-review"'
contains 'CI public body'
echo 'Native Magazine review: cover before article body before fact sheet; empty field omission passed.'

# Assert the semantic H1, not just the presence of heading text.
assert_h1() {
  python3 - "$page" "$1" <<'PY'
from html.parser import HTMLParser
import sys
class Headings(HTMLParser):
    def __init__(self):
        super().__init__(); self.active=False; self.titles=[]
    def handle_starttag(self, tag, attrs):
        if tag=='h1': self.active=True; self.titles.append('')
    def handle_data(self, data):
        if self.active: self.titles[-1]+=data
    def handle_endtag(self, tag):
        if tag=='h1': self.active=False
p=Headings(); p.feed(open(sys.argv[1]).read())
assert [' '.join(title.split()) for title in p.titles]==[sys.argv[2]],p.titles
PY
}

# RC10: actual menu heading and inherited Itemid isolation.
db "UPDATE ci_menu SET link='index.php?option=com_content&view=category&layout=magazine-list&id=$catid',params=JSON_SET(params,'$.show_page_heading',1,'$.page_heading','CI editorial heading','$.op_masthead_image','images/menu-banner.jpg','$.op_masthead_subtitle','CI menu subtitle','$.op_masthead_height','normal') WHERE id=$category_menu;"
caturl="$url/index.php?option=com_content&view=category&layout=magazine-list&id=$catid&Itemid=$category_menu"
curl -fsSL "$caturl" > "$page"
contains 'op-mag-masthead--internal op-mag-masthead--normal'
contains 'images/menu-banner.jpg'
contains 'CI menu subtitle'
contains 'op-mag-has-masthead'
# The category still owns its masthead H1 (native duplicate is visually suppressed).
for layout in default magazine-article magazine-review magazine-interview magazine-longform; do
  curl -fsSL "$url/index.php?option=com_content&view=article&layout=$layout&id=$article&Itemid=$category_menu" > "$page"
  absent 'op-mag-masthead--internal'
  contains 'CI public body'
  assert_h1 'CI SEO article'
done
db "UPDATE ci_menu SET params=JSON_SET(params,'$.show_page_heading',0) WHERE id=$category_menu;"
curl -fsSL "$caturl" > "$page"
absent 'op-mag-masthead--internal'
db "UPDATE ci_menu SET params=JSON_SET(params,'$.show_page_heading',1,'$.page_heading','','$.op_masthead_image','https://evil.example/invalid.jpg','$.op_masthead_height','invalid') WHERE id=$category_menu; UPDATE ci_template_styles SET params=JSON_SET(params,'$.magazineBanner','images/fallback-banner.jpg') WHERE id=$mid;"
curl -fsSL "$caturl" > "$page"
contains 'op-mag-masthead--internal op-mag-masthead--compact'
contains 'images/fallback-banner.jpg'
contains 'CI category'
absent 'evil.example/invalid.jpg'
db "UPDATE ci_menu SET link='index.php?option=com_content&view=article&layout=magazine-review&id=$article',params=JSON_SET(params,'$.page_heading','CI editorial review heading') WHERE id=$category_menu;"
curl -fsSL "$reviewurl" > "$page"
contains 'op-mag-masthead--internal'
contains 'CI editorial review heading'
contains 'op-mag-masthead__context'
contains 'CI SEO article'
contains 'CI public body'
assert_h1 'CI SEO article'
echo 'Native menu heading: direct category/article, per-menu fields, fallback, off and inherited Itemid passed.'

# RC11: real native breadcrumb, reading time in metadata, archive filters and thumbnails.
db "INSERT INTO ci_modules (title,position,published,module,access,showtitle,params,client_id,language) VALUES ('CI breadcrumb','breadcrumb',1,'mod_breadcrumbs',1,0,JSON_OBJECT('showHere',0,'showHome',1,'showLast',1,'cache',0),0,'*'); INSERT INTO ci_modules_menu (moduleid,menuid) VALUES (LAST_INSERT_ID(),$category_menu);"
db "UPDATE ci_menu SET params=JSON_SET(params,'$.show_page_heading',0,'$.show_author',1,'$.show_publish_date',1,'$.info_block_position',0) WHERE id=$category_menu; UPDATE ci_content SET attribs=JSON_SET(attribs,'$.show_author',1,'$.show_publish_date',1,'$.info_block_position',0) WHERE id=$article;"
curl -fsSL "$reviewurl" > "$page"
contains 'op-mag-masthead--breadcrumb-only'
contains 'op-mag-masthead__breadcrumb'
contains 'images/fallback-banner.jpg'
contains 'mod-breadcrumbs'
contains 'op-mag-reading-time'
python3 - "$page" <<'PY'
import re,sys
s=open(sys.argv[1]).read()
assert re.search(r'<div class="op-mag-byline"[^>]*>[\s\S]*?<span class="op-mag-reading-time">',s), 'Reading time not integrated in editorial metadata'
byline=s.split('<div class="op-mag-byline"',1)[1].split('</div>',1)[0]
assert 'op-mag-byline__author' in byline and 'op-mag-byline__date' in byline and '<time datetime=' in byline, byline
assert 'icon-' not in byline and 'Written by' not in byline and 'Published:' not in byline, byline
assert s.count('class="op-mag-reading-time"')==1
assert s.index('op-mag-masthead__breadcrumb') < s.index('id="main-content"')
PY
db "UPDATE ci_menu SET params=JSON_SET(params,'$.show_page_heading',1,'$.page_heading','CI combined heading','$.op_masthead_subtitle','CI combined subtitle') WHERE id=$category_menu;"
curl -fsSL "$reviewurl" > "$page"
contains 'op-mag-masthead--internal'
contains 'op-mag-masthead__breadcrumb'
absent 'op-mag-masthead--breadcrumb-only'
python3 - "$page" <<'PY'
import sys
s=open(sys.argv[1]).read().split('<header class="op-mag-masthead',1)[1].split('</header>',1)[0]
assert s.index('op-mag-masthead__context') < s.index('CI combined subtitle') < s.index('op-mag-masthead__breadcrumb'), s
assert '<h1' not in s, s
PY
assert_h1 'CI SEO article'
db "DELETE FROM ci_modules_menu WHERE moduleid IN (SELECT id FROM ci_modules WHERE title='CI breadcrumb'); DELETE FROM ci_modules WHERE title='CI breadcrumb';"
db "UPDATE ci_menu SET params=JSON_SET(params,'$.show_page_heading',0) WHERE id=$category_menu;"
curl -fsSL "$reviewurl" > "$page"
absent 'op-mag-masthead--breadcrumb-only'
# Archive fixtures are separate from published articles; retain native filtering and ACL.
for kind in pictured plain restricted; do
  access=1; img="{}"
  if [[ "$kind" == pictured ]]; then img='{"image_intro":"images/archive-thumb.jpg"}'; fi
  if [[ "$kind" == restricted ]]; then access=2; img='{"image_intro":"images/private-archive.jpg"}'; fi
  db "INSERT INTO ci_content (title,alias,introtext,\`fulltext\`,state,catid,created,created_by,modified,publish_up,images,urls,attribs,metadesc,access,metadata,language) VALUES ('CI archived $kind','ci-archive-$kind','<p>CI archive intro</p>','',2,$catid,UTC_TIMESTAMP(),$author,UTC_TIMESTAMP(),UTC_TIMESTAMP(),'$img','{}','{}','',$access,'{}','*');"
done
db "UPDATE ci_menu SET link='index.php?option=com_content&view=archive&layout=magazine-archive',params=JSON_SET(params,'$.show_page_heading',1,'$.page_heading','CI archive heading','$.show_category',1,'$.show_publish_date',1,'$.show_intro',1,'$.show_noauth',0,'$.filter_field','title','$.link_titles',1,'$.display_num',10) WHERE id=$category_menu;"
archiveurl="$url/index.php?option=com_content&view=archive&layout=magazine-archive&Itemid=$category_menu"
curl -fsSL "$archiveurl" > "$page"
contains 'op-mag-archive__item--image'
contains 'images/archive-thumb.jpg'
contains 'CI archived pictured'
contains 'CI archived plain'
contains 'op-mag-category-info'
contains 'com-content-archive__filters'
absent 'images/private-archive.jpg'
absent 'CI archived restricted'
curl -fsSL "$archiveurl&filter-search=pictured" > "$page"
contains 'CI archived pictured'
absent 'CI archived plain'
db "DELETE FROM ci_content WHERE alias IN ('ci-archive-pictured','ci-archive-plain','ci-archive-restricted');"
echo 'Native RC11 breadcrumb, metadata byline, archive images and filter passed.'
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

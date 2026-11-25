<?php
declare(strict_types=1);
define('_JEXEC',1);
require __DIR__.'/../html/seo.php';
function check($ok,$label){if(!$ok)throw new RuntimeException($label);}
final class Doc {
 public array $tags=[],$links=[];
 public string $description='';
 public function getMetaData($k,$a='name'){return $this->tags[$a][$k]??'';}
 public function setMetaData($k,$v,$a='name'){$this->tags[$a][$k]=$v;}
 public function getTitle(){return 'Opera & cultura';}
 public function getDescription(){return $this->description;}
 public function setDescription($d){$this->description=$d;}
 public function getHeadData(){return ['links'=>$this->links];}
 public function addHeadLink($url,$rel){$this->links[$url]=['relation'=>$rel];}
}
$root='https://example.invalid/sub/';
foreach(['javascript:alert(1)','//evil.invalid/x','https://user@evil.invalid/a','data:image/png,x','../x','https://example.invalid/"bad'] as $url){check(opSeoUrl($url,$root)==='','Unsafe '.$url);}
check(opSeoUrl('{"imagefile":"images/opera.jpg#joomlaImage://x"}',$root)===$root.'images/opera.jpg','Media field');
check(opSeoCanonical($root,'/sub/category?start=20&utm_source=x&filter=opera&fbclid=x')==='https://example.invalid/sub/category?start=20&filter=opera','Tracking/pagination');
$item=(object)['params'=>new class{public function get($k,$d){return true;}},'state'=>1,'images'=>json_encode(['image_fulltext'=>'images/full.jpg','image_fulltext_alt'=>'Ritratto','image_intro'=>'images/intro.jpg']),'introtext'=>'<p>La città e l’opera.</p>','publish_up'=>'2026-01-01 12:00:00'];
$d=new Doc;opSeoArticle($d,$item,$root);opSeoComplete($d,$root,'/sub/article?utm_medium=x','Opera+','it-IT',['images/menu.jpg']);
check($d->getMetaData('og:image','property')===$root.'images/full.jpg','Article image priority');
check($d->getMetaData('og:type','property')==='article','Article type');
check($d->getMetaData('og:description','property')==='La città e l’opera.','Description');
check($d->getMetaData('article:published_time','property')==='2026-01-01T12:00:00+00:00','Date');
check($d->getMetaData('twitter:card')==='summary_large_image','Twitter card');
check(count($d->links)===1,'Canonical');
$d=new Doc;$d->setMetaData('og:image','https://example.invalid/plugin.jpg','property');$d->setMetaData('og:title','SEO plugin','property');$d->links['https://example.invalid/preferred']=['relation'=>'canonical'];opSeoArticle($d,$item,$root);opSeoComplete($d,$root,'/sub/article','Opera+','it-IT',[]);
check($d->getMetaData('og:title','property')==='SEO plugin'&&$d->getMetaData('og:image','property')==='https://example.invalid/plugin.jpg','Plugin preservation');
check(count($d->links)===1&&$d->getMetaData('og:url','property')==='https://example.invalid/preferred','Canonical preservation');
$d=new Doc;$item->params=new class{public function get($k,$d){return false;}};opSeoArticle($d,$item,$root);opSeoComplete($d,$root,'/sub/article','Opera+','it-IT',['images/fallback.jpg']);
check($d->getMetaData('og:type','property')==='website'&&$d->getMetaData('og:image','property')===$root.'images/fallback.jpg','Restricted article');
echo "SEO metadata, URLs, canonical, permissions and plugin preservation passed.\n";

$item->params=new class{public function get($k,$d){return true;}};
$item->publish_up='2099-01-01 00:00:00';
$d=new Doc;opSeoArticle($d,$item,$root);
check(!$d->getMetaData('og:image','property'),'Future article must not publish social image');
$item->publish_up='2026-01-01 12:00:00';$item->publish_down='2026-01-02 00:00:00';
$d=new Doc;opSeoArticle($d,$item,$root);
check(!$d->getMetaData('og:image','property'),'Expired article must not publish social image');

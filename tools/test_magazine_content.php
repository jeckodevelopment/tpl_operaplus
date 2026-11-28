<?php
declare(strict_types=1);
define('_JEXEC', 1);
require __DIR__.'/../html/magazine/content.php';
function check($condition,$label){if(!$condition)throw new RuntimeException($label);}
check(opMagazineReadingMinutes('')===0,'Empty reading time');
check(opMagazineReadingMinutes(str_repeat('opera ',200))===1,'200 words');
check(opMagazineReadingMinutes(str_repeat('città ',201))===2,'Unicode ceil');
check(opMagazineReadingMinutes('<p>'.str_repeat('opera ',50).'</p><script>'.str_repeat('ignored ',300).'</script>',50)===1,'HTML and scripts');
check(opMagazineReadingMinutes(str_repeat('opera ',201),400)===1,'Configured speed');
foreach(['article'=>['article','longform','interview','review'],'category'=>['grid','list','dossier']] as $view=>$modes){
 foreach($modes as $mode){
  $root=__DIR__.'/../html/com_content/'.$view.'/magazine-'.$mode;
  check(is_file($root.'.php')&&is_file($root.'.xml'),'Missing native layout');
  $xml=simplexml_load_file($root.'.xml');
  check($xml!==false&&isset($xml->fields),'Invalid menu metadata');
 }
}
echo "Reading time and seven native menu layout metadata checks passed.\n";

require __DIR__.'/../html/magazine/review.php';
final class TestParams { public function __construct(private int $display){} public function get($key,$default){return $this->display;} }
$field = static fn($name,$value,$display=0)=>(object)['name'=>$name,'rawvalue'=>$value,'params'=>new TestParams($display)];
$rows=opMagazineReviewRows([$field('review-cast',"A\nB"),$field('review-date','2026-11-25 00:00:00'),$field('review-opera','Rigoletto'),$field('review-city',''),$field('review-theatre','duplicate',2),$field('atlas-primary','opera:x'),$field('review-credits',['bad'])]);
check(array_keys($rows)===['opera','date','cast'],'Review rows order and filtering');
check($rows['date']==='2026-11-25','Performance date normalization');
check(opMagazineReviewRows([$field('review-date','2026-02-30')])===[],'Invalid calendar date');
check(opMagazineReviewRows([])===[],'Empty fact sheet');
foreach(['com_content/archive/magazine-archive','com_contact/contact/magazine-author'] as $file){
  $path=__DIR__.'/../html/'.$file;
  check(is_file($path.'.php')&&simplexml_load_file($path.'.xml')!==false,'New native metadata');
}
echo "Review fact sheet and archive/author layout metadata passed.\n";

require_once __DIR__.'/../html/seo.php';
$root = 'https://example.invalid/';
$intro = ['image_intro'=>'images/intro.webp#joomlaImage://local-images/intro.webp?width=800&height=450','image_intro_alt'=>'Teatro','image_intro_caption'=>'Didascalia','image_intro_alt_empty'=>1,'image_fulltext_alt'=>'Old alt','image_fulltext_caption'=>'Old caption'];
$original = json_encode($intro);
$rendered = json_decode(opMagazineArticleImages($original,true,true,$root),true);
foreach (['','_alt','_caption','_alt_empty'] as $suffix) {
    check($rendered['image_fulltext'.$suffix]===$intro['image_intro'.$suffix],'Intro metadata '.$suffix);
}
check(opMagazineArticleImages($original,false,true,$root)===$original,'Disabled');
check(opMagazineArticleImages($original,true,false,$root)===$original,'Restricted');
foreach (['{}','invalid',json_encode(['image_intro'=>'javascript:alert(1)']),json_encode($intro+['image_fulltext'=>'images/full.jpg'])] as $unchanged) {
    check(opMagazineArticleImages($unchanged,true,true,$root)===$unchanged,'No fallback when no safe intro or explicit full image');
}
$bare = json_decode(opMagazineArticleImages(json_encode(['image_intro'=>'images/intro.jpg','image_fulltext'=>'','image_fulltext_alt'=>'stale']),true,true,$root),true);
check($bare['image_fulltext']==='images/intro.jpg'&&!isset($bare['image_fulltext_alt']),'No stale full metadata');
echo "Magazine introductory image fallback checks passed.\n";

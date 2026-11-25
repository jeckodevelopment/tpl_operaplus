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

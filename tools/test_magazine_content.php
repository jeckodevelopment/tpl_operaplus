<?php
declare(strict_types=1);
define('_JEXEC', 1);
require __DIR__.'/../html/magazine/content.php';
function check($condition,$label){if(!$condition)throw new RuntimeException($label);}
check(opMagazineReadingMinutes('')===0,'Empty reading time');
check(opMagazineReadingMinutes(str_repeat('opera ',200))===1,'200 words');
check(opMagazineReadingMinutes(str_repeat('città ',201))===2,'Unicode ceil');
check(opMagazineReadingMinutes("<p>L’opera è qui.</p><script>ignored text</script>",3)===1,'HTML and scripts');
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

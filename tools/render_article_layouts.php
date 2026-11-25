<?php
declare(strict_types=1);
namespace Joomla\CMS\HTML { final class HTMLHelper { public static function cleanImageURL($url){return (object)['url'=>explode('#',$url)[0]];} } }
namespace Joomla\CMS\Uri { final class Uri { public static function root(){return 'https://example.invalid/';} } }
namespace Joomla\CMS\Language { final class Text { public static function _($key){return $key;} } }
namespace {
define('_JEXEC',1);
define('JPATH_ROOT',__DIR__.'/../dist/fixture-core');
@mkdir(JPATH_ROOT.'/modules/mod_articles/tmpl',0777,true);
file_put_contents(JPATH_ROOT.'/modules/mod_articles/tmpl/default.php','<?php echo "NATIVE-DEFAULT";');
final class Params {
 public function __construct(private array $p){}
 public function get($k,$d=null){return $this->p[$k]??$d;}
 public function set($k,$v){$this->p[$k]=$v;}
}
final class App {
 public function __construct(private string $mode){}
 public function getTemplate($full){return (object)['params'=>new Params(['presentationMode'=>$this->mode])];}
}
function render($layout,$list,$mode='magazine',$overrides=[],$grouped=false){
 $app=new App($mode);
 $params=new Params(array_replace(['item_title'=>1,'link_titles'=>1,'item_heading'=>'h3','img_intro_full'=>'intro','show_introtext'=>1,'layout_columns'=>3],$overrides));
 ob_start();require __DIR__.'/../html/mod_articles/magazine-'.$layout.'.php';return ob_get_clean();
}
function check($ok,$msg){if(!$ok)throw new \RuntimeException($msg);}
@mkdir(__DIR__.'/../dist/magazine',0777,true);
file_put_contents(__DIR__.'/../dist/magazine/fixture.svg','<svg xmlns="http://www.w3.org/2000/svg" width="800" height="500"><rect width="800" height="500" fill="#eee8e1"/><path d="M0 500L400 70 800 500Z" fill="#9b6d64"/></svg>');
$list=[];
foreach(['Una serata a teatro','La musica e la città','Intervista al direttore'] as $i=>$title){
 $list[]=(object)['title'=>$title,'link'=>'#article-'.$i,'imageSrc'=>'/fixture.svg','images'=>json_encode(['image_intro_alt'=>'Teatro']),'displayCategoryTitle'=>'Recensioni','displayCategoryLink'=>'#category','displayDate'=>'25 novembre 2026','displayAuthorName'=>'Redazione','displayIntrotext'=>'<p>Un approfondimento sul mondo dell’opera.</p>','event'=>(object)[]];
}
$layouts=['cover','sidebar','grid','latest','list','portraits','dossier','ranked','topics','related-sidebar','related-horizontal','related-compact','carousel','mosaic'];
$html='';
foreach($layouts as $layout){
 $out=render($layout,$list,'magazine',$layout==='carousel'?['layout_columns'=>2]:[]);
 check(substr_count($out,'itemscope')===($layout==='cover'?1:3),'Card count '.$layout);
 check(str_contains($out,'25 novembre 2026'),'Metadata absent '.$layout);
 check(render($layout,$list,'standard')==='NATIVE-DEFAULT','Standard fallback');
 check(render($layout,$list,'magazine',[],true)==='NATIVE-DEFAULT','Grouped fallback');
 check(render($layout,[])==='','Empty layout');
 if($layout==='carousel'){check(str_contains($out,'data-op-mag-prev')&&str_contains($out,'tabindex="0"'),'Carousel controls missing');}
 $html.='<section data-case="'.$layout.'"><h2>'.$layout.'</h2>'.$out.'</section>';
}
$unsafe=clone $list[0];$unsafe->title='<script>unsafe</script> & title';$unsafe->link='javascript:alert(1)';$unsafe->imageSrc='https://example.invalid@evil.invalid/x';$unsafe->displayCategoryLink='//evil.invalid/x';
$bad=render('cover',[$unsafe]);check(!str_contains($bad,'javascript:')&&!str_contains($bad,'<script>')&&!str_contains($bad,'evil.invalid')&&!str_contains($bad,'<img'),'Unsafe fields emitted');
check(str_contains($bad,'op-mag-card--no-image'),'No-image fallback');
check(!str_contains(render('grid',$list,'magazine',['link_titles'=>0]),'href="#article-'),'Disabled title links ignored');
check(!str_contains(render('grid',$list,'magazine',['img_intro_full'=>'none']),'<img'),'Disabled images ignored');
$html.='<section data-case="no-image">'.$bad.'</section>';
$css=file_get_contents(__DIR__.'/../media/css/template.css').file_get_contents(__DIR__.'/../media/css/magazine.css');
$html=str_replace('src="/fixture.svg"','src="fixture.svg"',$html);
file_put_contents(__DIR__.'/../dist/magazine/articles.html','<!doctype html><html lang="it"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><style>'.$css.'section[data-case]{max-width:900px;margin:20px auto;padding:0 16px}section[data-case="sidebar"]{max-width:360px}</style><body class="operaplus op-magazine theme-light"><main class="op-mag-main">'.$html.'</main><script>'.file_get_contents(__DIR__.'/../media/js/template.js').'</script></body></html>');
echo "Native article layouts: rendering, fallback, options and URL escaping passed.\n";
}

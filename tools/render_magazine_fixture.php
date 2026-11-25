<?php
declare(strict_types=1);
namespace Joomla\CMS\Language { final class Text { public static function _(string $key): string {return $key;} } }
namespace {
define('_JEXEC',1);
require __DIR__.'/../html/magazine/context.php';
function check(bool $ok,string $message): void {if (!$ok) throw new \RuntimeException($message);}
final class Input {
 public function __construct(private array $values){}
 public function getCmd($key): string {return (string) ($this->values[$key]??'');}
 public function getInt($key): int {return (int) ($this->values[$key]??0);}
}
$active=(object)['id'=>44,'query'=>['option'=>'com_content','view'=>'featured']];
$default=(object)['id'=>1];
check(opMagazineLanding($active,$default,44,new Input(['option'=>'com_content','view'=>'featured'])),'Dedicated landing not recognized');
check(!opMagazineLanding($active,$default,44,new Input(['option'=>'com_content','view'=>'article','id'=>1])),'Inherited Itemid misclassified article');
check(!opMagazineLanding($active,$default,0,new Input(['option'=>'com_content','view'=>'featured'])),'Non-home default misclassified');
check(!opMagazineLanding(null,$default,44,new Input([])),'Missing active menu');
final class Params {
 public function get($key,$default=''){return $key==='magazineSubtitle'?'Approfondimenti, recensioni, interviste e storie dal mondo dell’opera.':$default;}
}
final class Document {
 public $params;
 public array $calls=[];
 public function __construct(private array $positions){$this->params=new Params();}
 public function countModules($name){return array_key_exists($name,$this->positions)?1:0;}
 public function getBuffer($type,$name,$attributes){$this->calls[]=$name;return $this->positions[$name];}
 public function render(bool $magazineLanding): string {
  $assetUrl=static fn($v)=>'';
  $mediaFile=static fn($v)=>$v;
  $e=static fn($v)=>htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');
  ob_start();require __DIR__.'/../html/magazine/page.php';return ob_get_clean();
 }
}
$cover='<article class="fixture-cover">Copertina</article>';
$news='<div class="fixture-news">Notizie</div>';
$main='<div class="moduletable"><h2>In evidenza</h2><p>Sezione</p></div>';
$cta='<div class="op-cta-shell">Newsletter</div>';
$doc=new Document(['magazine-cover'=>$cover,'magazine-news'=>$news,'magazine-featured'=>$main,'magazine-featured-aside'=>$cta,'magazine-reviews'=>$main,'magazine-reviews-aside'=>'']);
$home=$doc->render(true);
check(count(array_unique($doc->calls))===count($doc->calls),'Position rendered twice');
check(substr_count($home,'op-mag-row__aside')===2,'Empty home aside rendered');
check(!str_contains($home,'type="component"'),'Populated landing unexpectedly renders component');
$doc=new Document(['magazine-sidebar'=>'','sidebar-right'=>'']);
$empty=$doc->render(false);
check(!str_contains($empty,'op-mag-row__aside')&&str_contains($empty,'op-mag-row--full'),'Empty contextual sidebar not collapsed');
check(str_contains($empty,'type="component"'),'Component missing');
$emptyHome=(new Document([]))->render(true);
check(str_contains($emptyHome,'type="component"'),'Empty landing needs component fallback');
$nativeMenu='<div class="moduletable"><h3>Main Menu</h3><ul class="mod-menu mod-list menu"><li class="current active parent"><a href="#home" aria-current="page">Home</a><ul class="mod-menu__sub"><li><a href="#sub">Submenu</a></li></ul></li><li><a href="#courses">Corsi</a></li></ul></div>';
$nativeLogin='<div class="moduletable"><h3>Login Form</h3><form class="mod-login"><div class="mod-login__userdata"><div class="mod-login__username form-group"><div class="input-group"><input type="text" class="form-control" id="modlgn-username-1" placeholder="Nome utente"><label for="modlgn-username-1" class="visually-hidden">Nome utente</label><span class="input-group-text"><span class="icon-user"></span></span></div></div><div class="mod-login__password form-group"><div class="input-group"><input type="password" class="form-control" name="password" placeholder="Password"><button type="button" class="btn btn-secondary input-password-toggle"><span class="icon-eye"></span><span class="visually-hidden">Mostra password</span></button></div></div><div class="mod-login__remember form-group"><div class="form-check"><input type="checkbox" id="form-login-input-remember-1"><label class="form-check-label" for="form-login-input-remember-1">Ricordami</label></div></div><button class="btn btn-primary" type="submit">Accesso</button><ul class="mod-login__options list-unstyled"><li><a href="#reset">Hai dimenticato la password?</a></li></ul></div></form></div>';
$detail=(new Document(['magazine-sidebar'=>$nativeMenu.$nativeLogin.'<div class="moduletable">'.$cta.'</div>']))->render(false);
$header='<header class="op-header"><div class="op-container op-header-inner"><a class="op-brand" href="#home">Opera+</a><button class="op-menu-toggle" aria-expanded="false" aria-controls="op-navigation"><span></span><span></span><span></span></button><nav class="op-navigation" id="op-navigation"><ul class="mod-menu menu"><li class="current active parent"><a href="#home" aria-current="page">Home</a><ul class="mod-menu__sub"><li><a href="#sub">Submenu</a></li></ul></li><li><a href="#courses">Corsi</a></li><li class="parent"><a href="#learn">Learn</a><ul class="mod-menu__sub"><li><a href="#lesson">Lezione</a></li></ul></li></ul></nav></div></header>';
check(str_contains($detail,'op-mag-row__aside')&&!str_contains($detail,'op-mag-masthead'),'Detail shell incorrect');
@mkdir(__DIR__.'/../dist/magazine',0777,true);
foreach(['home'=>$home,'detail'=>$detail,'empty'=>$empty] as $name=>$html){
 $html=str_replace(['<jdoc:include type="message" />','<jdoc:include type="component" />'],['','<article><h2>Articolo nativo</h2><p>Contenuto</p></article>'],$html);
 file_put_contents(__DIR__.'/../dist/magazine/'.$name.'.html','<!doctype html><html lang="it"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><style>'.file_get_contents(__DIR__.'/../media/css/template.css').file_get_contents(__DIR__.'/../media/css/magazine.css').'</style><body class="operaplus op-magazine theme-light is-internal" data-op-menu-mode="click">'.($name==='detail'?$header:'').$html.'<script>'.file_get_contents(__DIR__.'/../media/js/template.js').'</script></body></html>');
}
echo "Magazine route, rendered positions and fallback checks passed.\n";
}

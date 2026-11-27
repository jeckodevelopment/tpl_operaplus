<?php
/** @package Joomla.Site @subpackage Templates.operaplus */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$app = Factory::getApplication();
$wa = $this->getWebAssetManager();
$wa->useStyle('template.operaplus')->useScript('template.operaplus');
$isMagazine = $this->params->get('presentationMode', 'standard') === 'magazine';
if ($isMagazine) {
    $wa->useStyle('template.operaplus.magazine');
}
$brand = htmlspecialchars((string) $this->params->get('brandText', 'Opera+'), ENT_QUOTES, 'UTF-8');
$tagline = htmlspecialchars((string) $this->params->get('tagline', "L'opera lirica raccontata al grande pubblico"), ENT_QUOTES, 'UTF-8');
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$footerText = $e($this->params->get('footerText', 'Opera+ · L’opera lirica raccontata al grande pubblico'));
$footerHeading = $e($this->params->get('footerHeading', 'Associazione Opera+'));
$footerTaxId = $e($this->params->get('footerTaxId', '97925160018'));
$footerAddress = $e($this->params->get('footerAddress', 'via Federico Patetta 12, 10155 Torino'));
$footerEmailRaw = trim((string) $this->params->get('footerEmail', 'info@opera.plus'));
$footerPecRaw = trim((string) $this->params->get('footerPec', 'operaplus@pec.it'));
$footerEmail = $e($footerEmailRaw);
$footerPec = $e($footerPecRaw);
$footerTopLabel = $e($this->params->get('footerTopLabel', 'Torna all’inizio ↑'));
$accent = (string) $this->params->get('accent', '#b78e63');
$background = (string) $this->params->get('background', '#171316');
$accent = preg_match('/^#[0-9a-fA-F]{6}$/', $accent) ? $accent : '#b78e63';
$background = preg_match('/^#[0-9a-fA-F]{6}$/', $background) ? $background : '#171316';
$burgundy = (string) $this->params->get('burgundy', '#632b3a');
$ivory = (string) $this->params->get('ivory', '#f3ece1');
$burgundy = preg_match('/^#[0-9a-fA-F]{6}$/', $burgundy) ? $burgundy : '#632b3a';
$ivory = preg_match('/^#[0-9a-fA-F]{6}$/', $ivory) ? $ivory : '#f3ece1';
$width = max(900, min(1600, (int) $this->params->get('containerWidth', 1200)));
$megaWidth = (int) $this->params->get('megaWidth', 760);
$megaWidth = in_array($megaWidth, [600, 760, 960, 1100], true) ? $megaWidth : 760;
$megaColumns = max(2, min(4, (int) $this->params->get('megaColumns', 3)));
$menuMode = $this->params->get('menuMode', 'hover') === 'click' ? 'click' : 'hover';
$titleSizes = [];
foreach (['h1' => 48, 'h2' => 43, 'h3' => 28, 'h4' => 23, 'h5' => 19, 'heroH1' => 72] as $key => $fallback) {
    $titleSizes[$key] = max(15, min(88, (int) $this->params->get($key . 'Size', $fallback)));
}

require_once __DIR__ . '/html/seo.php';
$siteRoot = Uri::root();
$assetUrl = static function ($value) use ($siteRoot): string {
    $url = opSeoUrl($value, $siteRoot);
    // Branding remains site-local; use the same validation for all image fields.
    return $url !== '' && str_starts_with($url, $siteRoot) ? $url : '';
};
$logo = $assetUrl($this->params->get('logo', ''));
$heroLogo = $assetUrl($this->params->get('heroLogo', '')) ?: $logo;
$footerLogo = $assetUrl($this->params->get('footerLogo', '')) ?: $logo;
// Branding: favicon and Open Graph. Use Joomla's document metadata API to avoid duplicates.
$faviconValue = (string) ($this->params->get('faviconFile', '') ?: $this->params->get('faviconPath', ''));
$faviconUrl = opSeoUrl($faviconValue, $siteRoot);
$faviconPath = parse_url($faviconUrl, PHP_URL_PATH) ?: '';
$faviconExt = strtolower(pathinfo($faviconPath, PATHINFO_EXTENSION));
if (!in_array($faviconExt, ['ico', 'png', 'svg', 'gif'], true)) { $faviconUrl = ''; }
$faviconMime = ['ico' => 'image/x-icon', 'png' => 'image/png', 'svg' => 'image/svg+xml', 'gif' => 'image/gif'][$faviconExt] ?? 'image/x-icon';
$menu = $app->getMenu();
$activeMenu = $menu->getActive();
$defaultMenu = $menu->getDefault();
$seoMenuParams = $activeMenu ? $activeMenu->getParams() : null;
$seoMenuImage = $seoMenuParams ? ($seoMenuParams->get('og_image', '') ?: $seoMenuParams->get('menu_image', '')) : '';
opSeoComplete($this, $siteRoot, Uri::getInstance()->toString(['path','query']), (string) $this->params->get('brandText','Opera+'), (string) $this->language, [$seoMenuImage, $this->params->get('defaultOgImage','')]);
$hasLeftSidebar = (bool) $this->countModules('sidebar-left');
$hasRightSidebar = (bool) $this->countModules('sidebar-right');
require_once __DIR__ . '/html/magazine/context.php';
$isHome = opMagazineLanding($activeMenu, $defaultMenu, 0, $app->getInput());
$magazineClasses = '';
$pageTheme = $this->params->get('pageTheme', 'light') === 'light' ? 'light' : 'dark';
$showHomeComponent = (bool) $this->params->get('showHomeComponent', 0);
$magazineLanding = false;
if ($isMagazine) {
    require_once __DIR__ . '/html/magazine/options.php';
    $magazineClasses = opMagazineClasses($this->params);
    $magazineLanding = opMagazineLanding($activeMenu, $defaultMenu, (int) $this->params->get('magazineHomeMenu', 0), $app->getInput());
}
$brandHref = $siteRoot;
$hasEditorialNav = (bool) $this->countModules('menu');
$ecosystemNav = $isMagazine && $this->countModules('magazine-topnav')
    ? trim((string) $this->getBuffer('modules', 'magazine-topnav', ['style' => 'none']))
    : '';
if ($isMagazine && ($magazineHome = $menu->getItem((int) $this->params->get('magazineHomeMenu', 0)))) {
    $brandHref = Route::_('index.php?Itemid=' . (int) $magazineHome->id, false);
}
$showMain = $isMagazine || !$isHome || $showHomeComponent || (bool) $this->countModules('breadcrumb or main-top or sidebar-left or sidebar-right or main-bottom');
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($this->language, ENT_QUOTES, 'UTF-8'); ?>" dir="<?php echo htmlspecialchars($this->direction, ENT_QUOTES, 'UTF-8'); ?>">
<head>
<jdoc:include type="metas" />
<?php if ($faviconUrl !== '') : ?><link rel="icon" href="<?php echo $e($faviconUrl); ?>" type="<?php echo $e($faviconMime); ?>" /><?php endif; ?>
<?php $fontMode = $this->params->get('externalFont', 'auto'); if ($fontMode === 'google' || ($fontMode === 'auto' && !$isMagazine)) : ?>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />
<?php endif; ?>
<jdoc:include type="styles" />
<jdoc:include type="scripts" />
<style>:root{--op-accent:<?php echo $accent; ?>;--op-bg:<?php echo $background; ?>;--op-max:<?php echo $width; ?>px;--op-mega-width:<?php echo $megaWidth; ?>px;--op-mega-cols:<?php echo $megaColumns; ?>;--op-burgundy:<?php echo $burgundy; ?>;--op-ivory:<?php echo $ivory; ?>;<?php foreach (['h1','h2','h3','h4','h5','heroH1'] as $level) : ?>--op-<?php echo strtolower($level); ?>-size:<?php echo $titleSizes[$level]; ?>px;<?php endforeach; ?>}</style>
</head>
<body id="top" data-op-menu-mode="<?php echo $menuMode; ?>" class="operaplus<?php echo $isMagazine ? ' op-magazine ' . $magazineClasses : ''; ?> <?php echo $isHome ? 'is-home' : 'is-internal'; ?> theme-<?php echo $pageTheme; ?> <?php echo $this->params->get('stickyHeader', 1) ? 'sticky-header' : ''; ?>">
<a class="skip-link" href="<?php echo $showMain ? '#main-content' : ($this->countModules('hero') ? '#home-hero' : '#site-footer'); ?>"><?php echo Text::_('TPL_OPERAPLUS_SKIP'); ?></a>
<?php if ($this->countModules('topbar')) : ?><div class="op-topbar"><div class="op-container"><jdoc:include type="modules" name="topbar" style="none" /></div></div><?php endif; ?>
<header class="op-header"><div class="op-container op-header-inner<?php echo $ecosystemNav !== '' ? ' op-mag-dual-nav' : ''; ?>">
  <a class="op-brand" href="<?php echo $e($brandHref); ?>" aria-label="<?php echo $brand; ?> – <?php echo $e(Text::_('TPL_OPERAPLUS_HOME_LABEL')); ?>">
    <?php if ($logo) : ?><img src="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo $brand; ?>" /><?php else : ?><span class="op-wordmark"><?php echo $brand; ?></span><?php endif; ?>
  </a>
  <?php if ($this->countModules('header-actions')) : ?><div class="op-header-actions"><jdoc:include type="modules" name="header-actions" style="none" /></div><?php endif; ?>
  <?php if ($hasEditorialNav || $ecosystemNav !== '') : ?>
    <button type="button" class="op-menu-toggle" aria-expanded="false" aria-controls="op-navigation" aria-label="<?php echo Text::_('TPL_OPERAPLUS_MENU'); ?>"><span></span><span></span><span></span></button>
    <nav id="op-navigation" class="op-navigation" aria-label="<?php echo Text::_('TPL_OPERAPLUS_MENU'); ?>">
      <?php if ($ecosystemNav !== '') : ?>
        <?php if ($hasEditorialNav) : ?><div class="op-mag-editorial" role="group" aria-label="<?php echo $e(Text::_('TPL_OPERAPLUS_MAG_EDITORIAL_NAV')); ?>"><jdoc:include type="modules" name="menu" style="none" /></div><?php endif; ?>
        <div class="op-mag-topnav" role="group" aria-label="<?php echo $e(Text::_('TPL_OPERAPLUS_MAG_ECOSYSTEM_NAV')); ?>"><?php echo $ecosystemNav; ?></div>
      <?php else : ?><jdoc:include type="modules" name="menu" style="none" /><?php endif; ?>
    </nav>
  <?php endif; ?>
</div></header>
<?php if ($this->countModules('announcement')) : ?><div class="op-announcement"><div class="op-container"><jdoc:include type="modules" name="announcement" style="none" /></div></div><?php endif; ?>
<?php if (!$isMagazine && $isHome && $this->countModules('hero')) : ?>
<section id="home-hero" class="op-hero" aria-label="Presentazione Opera+">
  <div class="op-container op-hero-grid">
    <div class="op-hero-content"><jdoc:include type="modules" name="hero" style="none" /></div>
    <?php if ($this->countModules('hero-side') || $heroLogo) : ?>
    <aside class="op-hero-side">
      <?php if ($this->countModules('hero-side')) : ?><jdoc:include type="modules" name="hero-side" style="none" />
      <?php elseif ($heroLogo) : ?><img class="op-hero-logo" src="<?php echo $e($heroLogo); ?>" alt="<?php echo $brand; ?>" loading="eager" /><?php endif; ?>
    </aside>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>
<?php if (!$isMagazine && $isHome) : ?>
<?php if ($this->countModules('intro')) : ?><section class="op-section"><div class="op-container"><jdoc:include type="modules" name="intro" style="card" /></div></section><?php endif; ?>
<?php if ($this->countModules('content-highlights')) : ?><section class="op-section op-highlights"><div class="op-container"><jdoc:include type="modules" name="content-highlights" style="none" /></div></section><?php endif; ?>
<?php if ($this->countModules('mission')) : ?><section class="op-section op-mission" id="progetto"><div class="op-container"><jdoc:include type="modules" name="mission" style="none" /></div></section><?php endif; ?>
<?php if ($this->countModules('featured')) : ?><section class="op-section op-section-alt"><div class="op-container"><jdoc:include type="modules" name="featured" style="card" /></div></section><?php endif; ?>
<?php if ($this->countModules('story')) : ?><section class="op-section op-story" id="contenuti"><div class="op-container"><jdoc:include type="modules" name="story" style="none" /></div></section><?php endif; ?>
<?php if ($this->countModules('courses')) : ?><section class="op-section"><div class="op-container"><jdoc:include type="modules" name="courses" style="card" /></div></section><?php endif; ?>
<?php if ($this->countModules('partners')) : ?><section class="op-section op-partners"><div class="op-container"><jdoc:include type="modules" name="partners" style="none" /></div></section><?php endif; ?>
<?php if ($this->countModules('schools')) : ?><section class="op-section op-schools" id="scuole"><div class="op-container"><jdoc:include type="modules" name="schools" style="none" /></div></section><?php endif; ?>
<?php if ($this->countModules('association')) : ?><section class="op-section op-association" id="associazione"><div class="op-container"><jdoc:include type="modules" name="association" style="none" /></div></section><?php endif; ?>
<?php if ($this->countModules('contacts')) : ?><section class="op-section op-contacts" id="contatti"><div class="op-container"><jdoc:include type="modules" name="contacts" style="none" /></div></section><?php endif; ?>
<?php endif; ?>
<?php if ($isMagazine) : ?>
<?php require __DIR__ . '/html/magazine/page.php'; ?>
<?php elseif ($showMain) : ?>
<main id="main-content" class="op-main"><div class="op-container">
<?php if ($this->countModules('breadcrumb')) : ?><div class="op-breadcrumb"><jdoc:include type="modules" name="breadcrumb" style="none" /></div><?php endif; ?>
<?php if ($this->countModules('main-top')) : ?><jdoc:include type="modules" name="main-top" style="card" /><?php endif; ?>
<div class="op-content-grid <?php echo ($hasLeftSidebar && $hasRightSidebar ? 'has-both-sidebars' : ($hasLeftSidebar ? 'has-left-sidebar' : ($hasRightSidebar ? 'has-right-sidebar' : ''))); ?>">
<?php if ($this->countModules('sidebar-left')) : ?><aside class="op-sidebar"><jdoc:include type="modules" name="sidebar-left" style="html5" /></aside><?php endif; ?>
<div class="op-content"><jdoc:include type="message" /><?php if (!$isHome || $showHomeComponent) : ?><jdoc:include type="component" /><?php endif; ?></div>
<?php if ($this->countModules('sidebar-right')) : ?><aside class="op-sidebar"><jdoc:include type="modules" name="sidebar-right" style="html5" /></aside><?php endif; ?>
</div>
<?php if ($this->countModules('main-bottom')) : ?><jdoc:include type="modules" name="main-bottom" style="card" /><?php endif; ?>
</div></main>
<?php endif; ?>
<?php if ($this->countModules('pre-footer')) : ?><section class="op-prefooter"><div class="op-container"><jdoc:include type="modules" name="pre-footer" style="none" /></div></section><?php endif; ?>
<?php if ($this->countModules('bottom')) : ?><section class="op-section op-section-alt"><div class="op-container"><jdoc:include type="modules" name="bottom" style="card" /></div></section><?php endif; ?>
<footer id="site-footer" class="op-footer">
  <div class="op-container">
    <div class="op-footer-inner">
      <div class="op-footer-identity">
        <?php if ($footerLogo) : ?><img class="op-footer-logo" src="<?php echo $e($footerLogo); ?>" alt="<?php echo $brand; ?>" loading="lazy" />
        <?php else : ?><span class="op-footer-wordmark"><?php echo $brand; ?></span><?php endif; ?>
        <jdoc:include type="modules" name="footer-brand" style="none" />
      </div>
      <div class="op-footer-details">
        <strong><?php echo $footerHeading; ?></strong>
        <?php if ($footerTaxId !== '') : ?><p>C.F. <?php echo $footerTaxId; ?></p><?php endif; ?>
        <?php if ($footerAddress !== '') : ?><p>Sede legale: <?php echo $footerAddress; ?></p><?php endif; ?>
        <?php if (filter_var($footerEmailRaw, FILTER_VALIDATE_EMAIL)) : ?><p><a href="mailto:<?php echo $footerEmail; ?>"><?php echo $footerEmail; ?></a></p><?php endif; ?>
        <?php if (filter_var($footerPecRaw, FILTER_VALIDATE_EMAIL)) : ?><p>PEC: <a href="mailto:<?php echo $footerPec; ?>"><?php echo $footerPec; ?></a></p><?php endif; ?>
        <jdoc:include type="modules" name="footer-legal" style="none" />
      </div>
      <div class="op-footer-extras">
        <jdoc:include type="modules" name="footer" style="none" />
        <jdoc:include type="modules" name="footer-links" style="none" />
        <jdoc:include type="modules" name="footer-social" style="none" />
        <a class="op-backtop" href="#top"><?php echo $footerTopLabel; ?></a>
      </div>
    </div>
    <div class="op-footer-bottom"><?php echo $footerText; ?></div>
  </div>
</footer>
<jdoc:include type="modules" name="debug" style="none" />
</body></html>
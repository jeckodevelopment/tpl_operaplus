<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

// Render each position once using the native document renderer. Counting assigned
// modules is not enough: a contextual Atlas module may intentionally return ''.
$position = function (string $name): string {
    return $this->countModules($name) ? trim((string) $this->getBuffer('modules', $name, ['style' => 'html5'])) : '';
};
$magRows = [];
if ($magazineLanding) {
    foreach ([
        ['magazine-cover', 'magazine-news'],
        ['magazine-featured', 'magazine-featured-aside'],
        ['magazine-reviews', 'magazine-reviews-aside'],
        ['magazine-interviews', 'magazine-interviews-aside'],
        ['magazine-dossier', 'magazine-dossier-aside'],
        ['magazine-insights', 'magazine-insights-aside'],
        ['magazine-latest', 'magazine-latest-aside'],
    ] as [$mainPosition, $asidePosition]) {
        $mainHtml = $position($mainPosition);
        $asideHtml = $position($asidePosition);
        if ($mainHtml !== '' || $asideHtml !== '') {
            $magRows[] = [$mainPosition, $mainHtml, $asideHtml];
        }
    }
}
$magHasAside = (bool) array_filter($magRows, static fn ($row) => $row[2] !== '');
$magShowComponent = !$magazineLanding || !$magRows || (bool) $this->params->get('magazineShowComponent', 0);
$magSidebar = $magShowComponent ? $position('magazine-sidebar') . $position('sidebar-right') : '';
$magBreadcrumb = $position('breadcrumb');
$magTop = $position('main-top');
$magBottom = $position('main-bottom');
$magTitle = (string) $this->params->get('magazineTitle', 'Magazine');
$magSubtitle = (string) $this->params->get('magazineSubtitle', '');
$magBanner = $assetUrl($this->params->get('magazineBanner', ''));
?>
<?php if ($magazineLanding) : ?>
<header class="op-mag-masthead">
    <?php if ($magBanner !== '') : ?><img class="op-mag-masthead__image" src="<?php echo $e($magBanner); ?>" alt="" fetchpriority="high"><?php endif; ?>
    <div class="op-container op-mag-masthead__body">
        <h1><?php echo $e($magTitle); ?></h1>
        <?php if ($magSubtitle !== '') : ?><p><?php echo $e($magSubtitle); ?></p><?php endif; ?>
    </div>
</header>
<?php endif; ?>
<main id="main-content" class="op-mag-main"><div class="op-container">
    <jdoc:include type="message" />
    <?php if ($magBreadcrumb !== '') : ?><div class="op-breadcrumb"><?php echo $magBreadcrumb; ?></div><?php endif; ?>
    <?php echo $magTop; ?>
    <?php foreach ($magRows as [$mainPosition, $mainHtml, $asideHtml]) : ?>
        <div class="op-mag-row<?php echo $magHasAside ? '' : ' op-mag-row--full'; ?>" data-magazine-section="<?php echo $e($mainPosition); ?>">
            <?php if ($mainHtml !== '') : ?><div class="op-mag-row__main"><?php echo $mainHtml; ?></div><?php endif; ?>
            <?php if ($asideHtml !== '') : ?><aside class="op-mag-row__aside op-sidebar" aria-label="<?php echo $e(Text::_('TPL_OPERAPLUS_MAG_SIDEBAR')); ?>"><?php echo $asideHtml; ?></aside><?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if ($magShowComponent) : ?>
        <div class="op-mag-row op-mag-content<?php echo $magSidebar === '' ? ' op-mag-row--full' : ''; ?>">
            <div class="op-mag-row__main op-content"><jdoc:include type="component" /></div>
            <?php if ($magSidebar !== '') : ?><aside class="op-mag-row__aside op-sidebar" aria-label="<?php echo $e(Text::_('TPL_OPERAPLUS_MAG_SIDEBAR')); ?>"><?php echo $magSidebar; ?></aside><?php endif; ?>
        </div>
    <?php endif; ?>
    <?php echo $magBottom; ?>
</div></main>

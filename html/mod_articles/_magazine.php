<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

// Native Joomla continues to own selection, ordering, access and filtering.
if ($app->getTemplate(true)->params->get('presentationMode', 'standard') !== 'magazine'
    || !empty($grouped) || $params->get('title_only', 0) || !$params->get('item_title', 1)) {
    $params = clone $params;
    $params->set('layout', 'default');
    require JPATH_ROOT . '/modules/mod_articles/tmpl/default.php';
    return;
}
if (empty($list)) {
    return;
}
$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
require_once __DIR__ . '/../seo.php';
$plain = static fn ($value): string => opSeoText($value);
$safeUrl = static function ($value): string {
    $url = trim(html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    if ($url === '' || preg_match('/[\x00-\x20\x7f\\\\]/', $url) || str_starts_with($url, '//')) {
        return '';
    }
    $parts = parse_url($url);
    if ($parts === false || isset($parts['user']) || isset($parts['pass'])) {
        return '';
    }
    if (isset($parts['scheme']) && (!in_array(strtolower($parts['scheme']), ['http', 'https'], true) || empty($parts['host']))) {
        return '';
    }
    return $url;
};
$mode = $opMagazineLayout;
$columns = max(2, min(4, (int) $params->get('layout_columns', 3)));
$heading = (string) $params->get('item_heading', 'h3');
if (!in_array($heading, ['h2', 'h3', 'h4', 'h5', 'h6'], true)) {
    $heading = 'h3';
}
$items = $mode === 'cover' ? array_slice($list, 0, 1) : $list;
$noImage = in_array($mode, ['ranked', 'topics', 'related-compact'], true);
$compact = in_array($mode, ['sidebar', 'latest', 'ranked', 'topics', 'related-sidebar', 'related-compact'], true);
?>
<?php if ($mode === 'carousel') : ?>
<div class="op-mag-carousel" data-op-mag-carousel>
    <div class="op-mag-carousel__controls" hidden>
        <button type="button" data-op-mag-prev aria-label="<?php echo $e(Text::_('TPL_OPERAPLUS_MAG_CAROUSEL_PREV')); ?>">&#8592;</button>
        <button type="button" data-op-mag-next aria-label="<?php echo $e(Text::_('TPL_OPERAPLUS_MAG_CAROUSEL_NEXT')); ?>">&#8594;</button>
    </div>
<?php endif; ?>
<div <?php if ($mode === 'carousel') : ?>tabindex="0" role="region" aria-label="<?php echo $e(Text::_('TPL_OPERAPLUS_MAG_CAROUSEL')); ?>" <?php endif; ?>class="op-mag-articles op-mag-articles--<?php echo $e($mode); ?> op-mag-cols-<?php echo $columns; ?>">
<?php foreach (array_values($items) as $index => $item) :
    $link = $safeUrl($item->link ?? '');
    $title = $plain($item->title ?? '');
    $linked = $link !== '' && (bool) $params->get('link_titles', 1);
    $image = '';
    $alt = '';
    $imageMode = $params->get('img_intro_full', 'none');
    if (!$noImage && in_array($imageMode, ['intro', 'full'], true) && !empty($item->imageSrc)) {
        $images = json_decode((string) ($item->images ?? '{}'), true);
        $images = is_array($images) ? $images : [];
        $image = $safeUrl(HTMLHelper::cleanImageURL(html_entity_decode((string) $item->imageSrc, ENT_QUOTES, 'UTF-8'))->url);
        if ($image !== '' && !preg_match('~^(?:https?://|/)~i', $image)) {
            $image = Uri::root() . ltrim($image, '/');
        }
        $alt = $plain($images[$imageMode === 'intro' ? 'image_intro_alt' : 'image_fulltext_alt'] ?? '');
    }
    $category = $plain($item->displayCategoryTitle ?? '');
    $categoryLink = $safeUrl($item->displayCategoryLink ?? '');
    $date = $plain($item->displayDate ?? '');
    $author = $plain($item->displayAuthorName ?? '');
    $intro = !$compact && $params->get('show_introtext', 0) ? $plain($item->displayIntrotext ?? '') : '';
    ?>
    <article class="op-mag-card<?php echo $image !== '' ? ' op-mag-card--image' : ' op-mag-card--no-image'; ?>" itemscope itemtype="https://schema.org/Article">
        <?php if ($mode === 'ranked') : ?><span class="op-mag-card__rank" aria-hidden="true"><?php echo $index + 1; ?></span><?php endif; ?>
        <?php if ($image !== '') : ?>
        <div class="op-mag-card__visual">
            <img itemprop="image" src="<?php echo $e($image); ?>" alt="<?php echo $e($alt); ?>" <?php echo $mode === 'cover' ? 'fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async">
        </div>
        <?php endif; ?>
        <div class="op-mag-card__body">
            <?php if ($category !== '') : ?><div class="op-mag-card__category"><?php if ($categoryLink !== '') : ?><a href="<?php echo $e($categoryLink); ?>"><?php echo $e($category); ?></a><?php else : echo $e($category); endif; ?></div><?php endif; ?>
            <<?php echo $heading; ?> class="op-mag-card__title" itemprop="headline"><?php if ($linked) : ?><a itemprop="url" href="<?php echo $e($link); ?>" class="<?php echo !empty($item->active) ? 'active' : ''; ?>"><?php echo $e($title); ?></a><?php else : echo $e($title); endif; ?></<?php echo $heading; ?>>
            <?php if ($author !== '' || $date !== '' || !empty($item->displayHits)) : ?><div class="op-mag-card__meta"><?php if ($author !== '') : ?><span><?php echo $e($author); ?></span><?php endif; ?><?php if ($date !== '') : ?><span><?php echo $e($date); ?></span><?php endif; ?><?php if (!empty($item->displayHits)) : ?><span><?php echo $e(Text::_('JGLOBAL_HITS')); ?>: <?php echo (int) $item->displayHits; ?></span><?php endif; ?></div><?php endif; ?>
            <?php echo $item->event->afterDisplayTitle ?? ''; ?>
            <?php echo $item->event->beforeDisplayContent ?? ''; ?>
            <?php if ($intro !== '') : ?><p class="op-mag-card__intro"><?php echo $e($intro); ?></p><?php endif; ?>
            <?php if ($params->get('show_tags', 0) && !empty($item->tags->itemTags)) : ?><?php echo LayoutHelper::render('joomla.content.tags', $item->tags->itemTags); ?><?php endif; ?>
            <?php echo $item->event->afterDisplayContent ?? ''; ?>
            <?php if ($link !== '' && $params->get('show_readmore', 0)) : ?><a class="op-mag-card__readmore" href="<?php echo $e($link); ?>"><?php echo $e($plain($item->displayReadmore ?? '') ?: Text::_('JGLOBAL_READ_MORE')); ?><span aria-hidden="true"> →</span></a><?php endif; ?>
        </div>
    </article>
<?php endforeach; ?>
</div>

<?php if ($mode === 'carousel') : ?></div><?php endif; ?>

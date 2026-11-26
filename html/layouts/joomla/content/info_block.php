<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

// Keep Joomla's original layout for every context except the explicitly marked
// Magazine article. The native article layout still owns metadata placement.
$item = $displayData['item'];
$params = $displayData['params'];
if (empty($item->opMagazineArticleByline)) {
    require JPATH_ROOT . '/layouts/joomla/content/info_block.php';
    return;
}
$position = $displayData['position'];
$block = (int) $params->get('info_block_position', 0);
$primary = ($position === 'above' && in_array($block, [0, 2], true)) || ($position === 'below' && $block === 1);
$secondary = ($position === 'above' && $block === 0) || ($position === 'below' && in_array($block, [1, 2], true));
$e = static fn ($v): string => htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$date = static function ($value, string $label = '') use ($e): string {
    if (!$value || str_starts_with((string) $value, '0000-00-00')) {
        return '';
    }
    $formatted = $e(HTMLHelper::_('date', $value, Text::_('DATE_FORMAT_LC3')));
    return '<time datetime="' . $e(HTMLHelper::_('date', $value, 'c')) . '">' . ($label === '' ? $formatted : Text::sprintf($label, $formatted)) . '</time>';
};
$category = static function ($title, $id, $lang, $linked) use ($e): string {
    $title = trim((string) $title);
    if ($title === '') { return ''; }
    return $linked && $id ? '<a href="' . $e(Route::_(RouteHelper::getCategoryRoute($id, $lang), false)) . '">' . $e($title) . '</a>' : $e($title);
};
$parts = [];
$categories = [];
$association = '';
if ($primary) {
    if ($params->get('show_parent_category') && !empty($item->parent_id)) {
        $categories[] = $category($item->parent_title ?? '', $item->parent_id, $item->parent_language ?? '*', $params->get('link_parent_category'));
    }
    if ($params->get('show_category')) {
        $categories[] = $category($item->category_title ?? '', $item->catid ?? 0, $item->category_language ?? '*', $params->get('link_category'));
    }
    $author = trim((string) (($item->created_by_alias ?? '') ?: ($item->author ?? '')));
    if ($params->get('show_author') && $author !== '') {
        $name = $e($author);
        if (!empty($item->contact_link) && $params->get('link_author')) {
            $name = '<a href="' . $e($item->contact_link) . '">' . $name . '</a>';
        }
        $parts[] = ['op-mag-byline__author', $name];
    }
    if ($params->get('show_publish_date')) {
        $parts[] = ['op-mag-byline__date', $date($item->publish_up ?? null)];
    }
    if ((int) ($item->opMagazineReadingMinutes ?? 0) > 0) {
        $parts[] = ['op-mag-reading-time', $e(Text::plural('TPL_OPERAPLUS_MAG_READING_MINUTES', (int) $item->opMagazineReadingMinutes))];
    }
    if ($params->get('show_associations')) {
        $association = $this->sublayout('associations', $displayData);
    }
}
if ($secondary) {
    if ($params->get('show_create_date')) { $parts[] = ['op-mag-byline__created', $date($item->created ?? null, 'COM_CONTENT_CREATED_DATE_ON')]; }
    if ($params->get('show_modify_date')) { $parts[] = ['op-mag-byline__modified', $date($item->modified ?? null, 'COM_CONTENT_LAST_UPDATED')]; }
    if ($params->get('show_hits')) { $parts[] = ['op-mag-byline__hits', $e(Text::sprintf('COM_CONTENT_ARTICLE_HITS', (int) ($item->hits ?? 0)))]; }
}
$categories = array_filter($categories);
$parts = array_filter($parts, static fn ($part) => $part[1] !== '');
if (!$categories && !$parts && $association === '') { return; }
?>
<div class="op-mag-article-meta">
    <?php if ($categories) : ?><div class="op-mag-card__category"><?php echo implode(' · ', $categories); ?></div><?php endif; ?>
    <?php if ($parts) : ?>
    <div class="op-mag-byline" role="group" aria-label="<?php echo $e(Text::_('COM_CONTENT_ARTICLE_INFO')); ?>">
        <?php foreach ($parts as [$class, $html]) : ?>
            <span class="op-mag-byline__item"><span class="<?php echo $class; ?>"><?php echo $html; ?></span></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if ($association !== '') : ?><dl class="op-mag-byline__associations"><?php echo $association; ?></dl><?php endif; ?>
</div>

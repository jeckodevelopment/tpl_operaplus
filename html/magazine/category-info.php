<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** Editorial category metadata; preserve the core option/position and route semantics. */
function opMagazineCategoryInfo($item, $params, string $position): string
{
    $block = (int) $params->get('info_block_position', 0);
    $primary = ($position === 'above' && in_array($block, [0, 2], true)) || ($position === 'below' && $block === 1);
    $secondary = ($position === 'above' && $block === 0) || ($position === 'below' && in_array($block, [1, 2], true));
    $e = static fn ($v): string => htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $category = static function ($title, $id, $language, $linked) use ($e): string {
        $label = $e($title);
        return $linked && $id ? '<a href="' . $e(Route::_(RouteHelper::getCategoryRoute($id, $language), false)) . '">' . $label . '</a>' : $label;
    };
    $date = static function ($value, $label = '') use ($e): string {
        if (!$value || str_starts_with((string) $value, '0000-00-00')) {
            return '';
        }
        $formatted = $e(HTMLHelper::_('date', $value, Text::_('DATE_FORMAT_LC3')));
        return '<time datetime="' . $e(HTMLHelper::_('date', $value, 'c')) . '">' . ($label ? Text::sprintf($label, $formatted) : $formatted) . '</time>';
    };
    $categories = [];
    $details = [];
    if ($primary) {
        if ($params->get('show_parent_category') && !empty($item->parent_id)) {
            $categories[] = $category($item->parent_title, $item->parent_id, $item->parent_language, $params->get('link_parent_category'));
        }
        if ($params->get('show_category')) {
            $categories[] = $category($item->category_title, $item->catid, $item->category_language, $params->get('link_category'));
        }
        if ($params->get('show_author') && !empty($item->author)) {
            $author = $e($item->created_by_alias ?: $item->author);
            if (!empty($item->contact_link) && $params->get('link_author')) {
                $author = '<a href="' . $e($item->contact_link) . '">' . $author . '</a>';
            }
            $details[] = $author;
        }
        if ($params->get('show_publish_date')) {
            $details[] = $date($item->publish_up);
        }
        if ($params->get('show_associations')) {
            $association = LayoutHelper::render('joomla.content.info_block.associations', ['item' => $item, 'params' => $params, 'position' => $position]);
        }
    }
    if ($secondary) {
        if ($params->get('show_create_date')) {
            $details[] = $date($item->created, 'COM_CONTENT_CREATED_DATE_ON');
        }
        if ($params->get('show_modify_date')) {
            $details[] = $date($item->modified, 'COM_CONTENT_LAST_UPDATED');
        }
        if ($params->get('show_hits')) {
            $details[] = $e(Text::sprintf('JGLOBAL_HITS', (int) $item->hits));
        }
    }
    $categories = array_filter($categories);
    $details = array_filter($details);
    if (!$categories && !$details && empty($association)) {
        return '';
    }
    $html = '<div class="op-mag-category-info">';
    if ($primary && $params->get('info_block_show_title', 1)) {
        $html .= '<span class="visually-hidden">' . $e(Text::_('COM_CONTENT_ARTICLE_INFO')) . '</span>';
    }
    if ($categories) {
        $html .= '<div class="op-mag-card__category">' . implode(' · ', $categories) . '</div>';
    }
    if ($details) {
        $html .= '<div class="op-mag-card__meta">' . implode('', array_map(static fn ($v) => '<span class="op-mag-category-info__detail">' . $v . '</span>', $details)) . '</div>';
    }
    if (!empty($association)) {
        $html .= '<dl class="op-mag-category-info__associations">' . $association . '</dl>';
    }
    return $html . '</div>';
}

<?php
defined('_JEXEC') or die;

/**
 * Menu assignment chooses the style. The active Itemid alone is not sufficient:
 * article detail URLs may inherit the landing page's menu context.
 */
function opMagazineLanding($active, $default, int $configured, $input): bool
{
    $target = $configured > 0 ? $configured : (int) ($default->id ?? 0);
    if (!$active || $target <= 0 || (int) $active->id !== $target) {
        return false;
    }
    $query = (array) ($active->query ?? []);
    foreach (['option', 'view'] as $key) {
        if (isset($query[$key]) && $input->getCmd($key) !== (string) $query[$key]) {
            return false;
        }
    }
    if (isset($query['id']) && $input->getInt('id') !== (int) $query['id']) {
        return false;
    }
    return $input->getCmd('view') !== 'article' || ($query['view'] ?? '') === 'article';
}

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


/** An internal masthead belongs to the actual menu destination, never inherited Itemid. */
function opMagazineRegisterMasthead($app): ?array
{
    $active = $app->getMenu()->getActive();
    $input = $app->getInput();
    $default = $app->getMenu()->getDefault();
    $style = $app->getTemplate(true)->params;
    if (!$active || opMagazineLanding($active, $default, (int) $style->get('magazineHomeMenu', 0), $input)
        || !opMagazineLanding($active, $default, (int) $active->id, $input)) {
        return null;
    }
    $query = (array) ($active->query ?? []);
    if (!isset($query['option'], $query['view']) || !in_array($query['option'].'.'.$query['view'],
        ['com_content.category','com_content.article','com_content.archive','com_contact.contact'], true)) {
        return null;
    }
    $params = $active->getParams();
    if ((string) $params->get('show_page_heading', '0') !== '1') {
        return null;
    }
    $title = trim((string) $params->get('page_heading', '')) ?: trim((string) $active->title);
    if ($title === '') { return null; }
    $heading = [
        'title' => $title,
        'subtitle' => trim((string) $params->get('op_masthead_subtitle', '')),
        'image' => (string) $params->get('op_masthead_image', ''),
        'height' => $params->get('op_masthead_height') === 'normal' ? 'normal' : 'compact',
    ];
    $app->set('operaplus.menuMasthead', $heading);
    return $heading;
}

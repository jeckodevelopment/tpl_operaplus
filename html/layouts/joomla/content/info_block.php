<?php
defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;

// Delegate native metadata, links and visibility to Joomla. Only the current
// Magazine article marks its own reading time; category/Standard are unchanged.
$opMinutes = (int) ($displayData['item']->opMagazineReadingMinutes ?? 0);
$opPosition = (int) $displayData['params']->get('info_block_position', 0);
$opHere = $displayData['position'] === ($opPosition === 1 ? 'below' : 'above');
if ($opMinutes <= 0 || !$opHere) {
    require JPATH_ROOT . '/layouts/joomla/content/info_block.php';
    return;
}
ob_start();
try {
    require JPATH_ROOT . '/layouts/joomla/content/info_block.php';
    $opInfo = ob_get_contents();
} finally {
    ob_end_clean();
}
$opReading = '<dd class="op-mag-reading-time">' . htmlspecialchars(Text::plural('TPL_OPERAPLUS_MAG_READING_MINUTES', $opMinutes), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</dd>';
echo str_replace('</dl>', $opReading . '</dl>', $opInfo);

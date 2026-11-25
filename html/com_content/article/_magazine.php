<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$opStyle = Factory::getApplication()->getTemplate(true)->params;
$opOriginalLayout = $this->getLayout();
$opMode = in_array($opContentMode, ['article', 'longform', 'interview', 'review'], true) ? $opContentMode : 'article';
$opEnabled = $opStyle->get('presentationMode', 'standard') === 'magazine'
    && strtolower((string) ($this->item->category_alias ?? '')) !== 'corsi';
$this->setLayout('default');
try {
    if (!$opEnabled) {
        require __DIR__ . '/default.php';
        return;
    }
    require_once __DIR__ . '/../../magazine/content.php';
    $opEvent = $this->item->event;
    $opAfterTitle = $opEvent->afterDisplayTitle ?? '';
    if ($this->item->params->get('access-view') && $opStyle->get('magazineReadingTime', 1)) {
        $opMinutes = opMagazineReadingMinutes((string) ($this->item->text ?? ''), (int) $opStyle->get('magazineReadingSpeed', 200));
        if ($opMinutes > 0) {
            $opEvent->afterDisplayTitle = $opAfterTitle . '<p class="op-mag-reading-time">' . htmlspecialchars(Text::plural('TPL_OPERAPLUS_MAG_READING_MINUTES', $opMinutes), ENT_QUOTES, 'UTF-8') . '</p>';
        }
    }
    echo '<div class="op-mag-article op-mag-article--' . $opMode . '">';
    try {
        require JPATH_SITE . '/components/com_content/tmpl/article/default.php';
    } finally {
        $opEvent->afterDisplayTitle = $opAfterTitle;
    }
    echo '</div>';
} finally {
    $this->setLayout($opOriginalLayout);
}

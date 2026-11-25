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
    require_once __DIR__ . '/../../seo.php';
    opSeoArticle($this->getDocument(), $this->item, \Joomla\CMS\Uri\Uri::root());
    require_once __DIR__ . '/../../magazine/content.php';
    $opEvent = $this->item->event;
    $opAfterTitle = $opEvent->afterDisplayTitle ?? '';
    if ($this->item->params->get('access-view') && $opStyle->get('magazineReadingTime', 1)) {
        $opMinutes = opMagazineReadingMinutes((string) ($this->item->text ?? ''), (int) $opStyle->get('magazineReadingSpeed', 200));
        if ($opMinutes > 0) {
            $opEvent->afterDisplayTitle = $opAfterTitle . '<p class="op-mag-reading-time">' . htmlspecialchars(Text::plural('TPL_OPERAPLUS_MAG_READING_MINUTES', $opMinutes), ENT_QUOTES, 'UTF-8') . '</p>';
        }
    }
    if ($opMode === 'review' && $this->item->params->get('access-view')) {
        require_once __DIR__ . '/../../magazine/review.php';
        $fields = \Joomla\Component\Fields\Administrator\Helper\FieldsHelper::getFields('com_content.article', $this->item, false);
        $levels = Factory::getApplication()->getIdentity()->getAuthorisedViewLevels();
        $rows = opMagazineReviewRows(array_values(array_filter($fields ?: [], static fn ($field) => in_array((int) ($field->access ?? 0), $levels, true))));
        if ($rows) {
            $e = static fn ($v): string => htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $panel = '<section class="op-mag-review" aria-label="' . $e(Text::_('TPL_OPERAPLUS_MAG_REVIEW_FACTS')) . '"><h2>' . $e(Text::_('TPL_OPERAPLUS_MAG_REVIEW_FACTS')) . '</h2><dl>';
            foreach ($rows as $name => $value) {
                $panel .= '<dt>' . $e(Text::_('TPL_OPERAPLUS_MAG_REVIEW_' . strtoupper($name))) . '</dt><dd>';
                $panel .= $name === 'date' ? '<time datetime="' . $e($value) . '">' . $e($value) . '</time>' : nl2br($e($value), false);
                $panel .= '</dd>';
            }
            $opEvent->afterDisplayTitle .= $panel . '</dl></section>';
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

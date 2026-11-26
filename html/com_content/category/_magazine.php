<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$opStyle = Factory::getApplication()->getTemplate(true)->params;
$opOriginalLayout = $this->getLayout();
$opMode = in_array($opContentMode, ['grid', 'list', 'dossier'], true) ? $opContentMode : 'grid';
$opEnabled = $opStyle->get('presentationMode', 'standard') === 'magazine'
    && strtolower((string) ($this->category->alias ?? '')) !== 'corsi';
$this->setLayout('blog');
try {
    if (!$opEnabled) {
        require __DIR__ . '/blog.php';
        return;
    }
    require_once __DIR__ . '/../../magazine/context.php';
    $opMasthead = opMagazineRegisterMasthead(Factory::getApplication());
    $opOriginalParams = $this->params;
    if ($opMasthead) {
        $this->params = clone $this->params;
        $this->params->set('show_page_heading', 1);
        $this->params->set('page_heading', $opMasthead['title']);
        if (trim((string) $this->category->title) === $opMasthead['title']) {
            $this->params->set('show_category_title', 0);
        }
    }
    $opColumns = max(1, min(4, (int) $this->params->get('num_columns', 3)));
    $opTint = $opMode === 'list' && $opStyle->get('magazineTintFirst', 0) ? ' op-mag-category--tint' : '';
    echo '<div class="op-mag-category op-mag-category--' . $opMode . ' op-mag-category--cols-' . $opColumns . $opTint . ($opMasthead ? ' op-mag-has-masthead' : '') . '">';
    require JPATH_SITE . '/components/com_content/tmpl/category/blog.php';
    echo '</div>';
} finally {
    if (isset($opOriginalParams)) { $this->params = $opOriginalParams; }
    $this->setLayout($opOriginalLayout);
}

<?php
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
$opLayout = $this->getLayout();
$opEnabled = Factory::getApplication()->getTemplate(true)->params->get('presentationMode', 'standard') === 'magazine';
$this->setLayout('default');
try {
    $opOriginalParams = $this->params;
    if ($opEnabled) {
        require_once __DIR__ . '/../../magazine/context.php';
        $opMasthead = opMagazineRegisterMasthead(Factory::getApplication());
        if ($opMasthead) {
            $this->params = clone $this->params;
            $this->params->set('show_page_heading', 1);
            $this->params->set('page_heading', $opMasthead['title']);
        }
        echo '<div class="op-mag-archive' . ($opMasthead ? ' op-mag-has-masthead' : '') . '">';
    }
    require JPATH_SITE . '/components/com_content/tmpl/archive/default.php';
    if ($opEnabled) { echo '</div>'; }
} finally {
    if (isset($opOriginalParams)) { $this->params = $opOriginalParams; }
    $this->setLayout($opLayout);
}

<?php
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
$opLayout = $this->getLayout();
$opEnabled = Factory::getApplication()->getTemplate(true)->params->get('presentationMode', 'standard') === 'magazine';
$this->setLayout('default');
try {
    if ($opEnabled) { echo '<div class="op-mag-archive">'; }
    require JPATH_SITE . '/components/com_content/tmpl/archive/default.php';
    if ($opEnabled) { echo '</div>'; }
} finally {
    $this->setLayout($opLayout);
}

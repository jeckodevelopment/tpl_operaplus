<?php
declare(strict_types=1);

// Native Joomla field loading and filter on the disposable CI installation.
define('_JEXEC', 1);
define('JPATH_BASE', $argv[1] . '/administrator');
require JPATH_BASE . '/includes/defines.php';
require JPATH_BASE . '/includes/framework.php';

use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormHelper;
use Joomla\Registry\Registry;

Form::addFieldPath(JPATH_SITE . '/templates/operaplus/field');
$field = FormHelper::loadFieldType('opstylemode', true);
if (!$field instanceof JFormFieldOpstylemode) {
    throw new RuntimeException('Style field failed to load');
}
foreach ([[(int) $argv[2], 'magazine', 'standard'], [(int) $argv[3], 'standard', 'magazine']] as [$id, $submitted, $expected]) {
    if ($field->filter($submitted, 'params', new Registry(['id' => $id])) !== $expected) {
        throw new RuntimeException('Stored style type was not preserved');
    }
}
echo "Native form field preserves stored style type.\n";

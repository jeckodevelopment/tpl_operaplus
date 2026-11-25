<?php
declare(strict_types=1);

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\HiddenField;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

/**
 * Preserve the installed style type on native Joomla style saves.
 * Duplication inherits the source type; the title and submitted value
 * are never used to choose a presentation.
 */
class JFormFieldOpstylemode extends HiddenField
{
    protected $type = 'Opstylemode';
    protected $hidden = true;

    public function filter($value, $group = null, ?Registry $input = null)
    {
        $id = (int) ($input?->get('id', 0) ?? 0);
        if ($id <= 0) {
            return 'standard';
        }

        $db = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->createQuery()
            ->select($db->quoteName('params'))
            ->from($db->quoteName('#__template_styles'))
            ->where($db->quoteName('id') . ' = :styleId')
            ->where($db->quoteName('template') . ' = ' . $db->quote('operaplus'))
            ->where($db->quoteName('client_id') . ' = 0')
            ->bind(':styleId', $id, ParameterType::INTEGER);
        $params = new Registry($db->setQuery($query)->loadResult() ?: '{}');

        return $params->get('presentationMode', 'standard') === 'magazine' ? 'magazine' : 'standard';
    }
}

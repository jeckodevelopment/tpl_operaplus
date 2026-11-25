<?php
defined('_JEXEC') or die;

function opMagazineReviewRows(array $fields): array
{
    // Names are a public contract for native Joomla custom fields. Only opt-in
    // fields with automatic display disabled are rendered by this panel.
    $names = ['opera', 'composer', 'theatre', 'city', 'date', 'season', 'conductor', 'director', 'orchestra', 'chorus', 'cast', 'credits'];
    $values = [];
    foreach ($fields as $field) {
        if (!str_starts_with((string) ($field->name ?? ''), 'review-') || !isset($field->params)
            || (int) $field->params->get('display', 2) !== 0) {
            continue;
        }
        $name = substr($field->name, 7);
        if (!in_array($name, $names, true)) { continue; }
        $raw = $field->rawvalue ?? null;
        if (!is_scalar($raw)) { continue; }
        $value = trim((string) $raw);
        if ($value === '') { continue; }
        if ($name === 'date') {
            if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})(?: \d{2}:\d{2}:\d{2})?$/D', $value, $m)
                || !checkdate((int) $m[2], (int) $m[3], (int) $m[1])) { continue; }
            $value = $m[1] . '-' . $m[2] . '-' . $m[3];
        }
        $values[$name] = $value;
    }
    return array_intersect_key(array_replace(array_fill_keys($names, ''), $values), $values);
}

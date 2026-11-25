<?php
declare(strict_types=1);
define('_JEXEC', 1);
define('JPATH_BASE', $argv[1] . '/administrator');
require JPATH_BASE . '/includes/defines.php';
require JPATH_BASE . '/includes/framework.php';

use Joomla\CMS\Language\Language;

$xml = simplexml_load_file(JPATH_SITE . '/templates/operaplus/templateDetails.xml');
foreach (['it-IT', 'en-GB'] as $tag) {
    $lang = Language::getInstance($tag);
    // Joomla skips global language copies when the core language pack is absent.
    // Keep local catalogs installed too and exercise the native style model fallback.
    if (!$lang->load('tpl_operaplus', JPATH_SITE, $tag, true, false)
        && !$lang->load('tpl_operaplus', JPATH_SITE . '/templates/operaplus', $tag, true, false)) {
        throw new RuntimeException('Cannot load ' . $tag);
    }
    foreach (['tpl_operaplus.ini', 'tpl_operaplus.sys.ini'] as $file) {
        if (!is_file(JPATH_SITE . '/templates/operaplus/language/' . $tag . '/' . $file)) {
            throw new RuntimeException('Missing installed local catalog: ' . $tag . '/' . $file);
        }
    }
    $expected = $tag === 'en-GB' ? 'Headings' : 'Titoli';
    if ($lang->_('TPL_OPERAPLUS_ADMIN_TYPOGRAPHY') !== $expected) {
        throw new RuntimeException('Incorrect localized tab: ' . $tag);
    }
    foreach ($xml->xpath('//config//*[@label or @description or @hint] | //config//option') as $node) {
        foreach ([(string) $node['label'], (string) $node['description'], (string) $node['hint'], $node->getName() === 'option' ? (string) $node : ''] as $key) {
            if (str_starts_with($key, 'TPL_OPERAPLUS_') && $lang->_($key) === $key) {
                throw new RuntimeException('Missing ' . $tag . ': ' . $key);
            }
        }
    }
}
echo "Both native template language catalogs resolve all administrator keys.\n";

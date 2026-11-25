<?php
declare(strict_types=1);

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;

return new class implements InstallerScriptInterface {
    public function install(InstallerAdapter $adapter): bool { return true; }
    public function update(InstallerAdapter $adapter): bool { return true; }
    public function uninstall(InstallerAdapter $adapter): bool { return true; }
    public function preflight(string $type, InstallerAdapter $adapter): bool { return true; }

    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
        if (!in_array($type, ['install', 'update', 'discover_install'], true)) {
            return true;
        }

        $db = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->createQuery()
            ->select('*')
            ->from($db->quoteName('#__template_styles'))
            ->where($db->quoteName('template') . ' = ' . $db->quote('operaplus'))
            ->where($db->quoteName('client_id') . ' = 0')
            ->order($db->quoteName('id') . ' ASC');
        $styles = $db->setQuery($query)->loadObjectList();

        // Do not rename, update or assign any existing style. A manually
        // created Magazine style counts regardless of its custom title.
        $byMode = [];
        $titles = [];
        foreach ($styles as $style) {
            $params = new Registry($style->params);
            $mode = $params->get('presentationMode', 'standard') === 'magazine' ? 'magazine' : 'standard';
            $titles[] = $style->title;
            if (!isset($byMode[$mode]) || ($style->home === '1' && $byMode[$mode]->home !== '1')) {
                $byMode[$mode] = $style;
            }
        }

        foreach (['standard', 'magazine'] as $mode) {
            if (isset($byMode[$mode])) {
                continue;
            }
            $source = $byMode['standard'] ?? $byMode['magazine'] ?? null;
            $params = new Registry($source->params ?? '{}');
            $params->set('presentationMode', $mode);
            $params->set('magazineHomeMenu', 0);
            if ($mode === 'magazine') {
                $params->set('pageTheme', 'light');
            }
            $base = 'Opera+ – ' . ucfirst($mode);
            $title = $base;
            for ($i = 2; in_array($title, $titles, true); $i++) {
                $title = $base . ' (' . $i . ')';
            }
            $record = (object) [
                'template' => 'operaplus',
                'client_id' => 0,
                'home' => '0',
                'title' => $title,
                'params' => (string) $params,
                'inheritable' => (int) ($source->inheritable ?? 1),
                'parent' => (string) ($source->parent ?? ''),
            ];
            $db->insertObject('#__template_styles', $record, 'id');
            $titles[] = $title;
            $byMode[$mode] = $record;
        }
        return true;
    }
};

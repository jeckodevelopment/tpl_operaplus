<?php
defined('_JEXEC') or die;

/** Enumerated style settings: never interpolate arbitrary administrator CSS. */
function opMagazineClasses($params): string
{
    $ratio = (string) $params->get('magazineImageRatio', 'classic');
    $overlay = (string) $params->get('magazineOverlay', 'classic');
    $ratio = in_array($ratio, ['classic', 'wide', 'square'], true) ? $ratio : 'classic';
    $overlay = in_array($overlay, ['classic', 'strong'], true) ? $overlay : 'classic';

    return 'op-mag-ratio-' . $ratio . ' op-mag-overlay-' . $overlay
        . ((bool) $params->get('magazineSeparators', 1) ? '' : ' op-mag-no-separators');
}

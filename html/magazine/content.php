<?php
defined('_JEXEC') or die;

function opMagazineReadingMinutes(string $html, int $speed = 200): int
{
    $speed = max(50, min(1000, $speed));
    $html = preg_replace('~<(script|style|noscript)\b[^>]*>.*?</\1\s*>~is', ' ', $html) ?? '';
    $text = html_entity_decode(strip_tags(preg_replace('/<[^>]+>/', ' ', $html) ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $count = preg_match_all("~[\p{L}\p{N}]+(?:['’\-][\p{L}\p{N}]+)*~u", $text);
    return $count > 0 ? (int) ceil($count / $speed) : 0;
}

/**
 * Supply the native full-image layout with introductory image data for this render only.
 * Preserve media metadata (including Joomla's image fragment) and explicit decorative alt.
 */
function opMagazineArticleImages(string $original, bool $enabled, bool $accessible, string $root): string
{
    if (!$enabled || !$accessible) {
        return $original;
    }
    $images = json_decode($original, true);
    if (!is_array($images) || !is_string($images['image_intro'] ?? null)
        || (isset($images['image_fulltext']) && (!is_string($images['image_fulltext']) || trim($images['image_fulltext']) !== ''))
        || opSeoUrl($images['image_intro'], $root) === '') {
        return $original;
    }
    $images['image_fulltext'] = $images['image_intro'];
    foreach (['_alt', '_alt_empty', '_caption'] as $suffix) {
        unset($images['image_fulltext' . $suffix]);
        if (array_key_exists('image_intro' . $suffix, $images)) {
            $images['image_fulltext' . $suffix] = $images['image_intro' . $suffix];
        }
    }
    return json_encode($images, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: $original;
}

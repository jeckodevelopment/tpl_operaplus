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

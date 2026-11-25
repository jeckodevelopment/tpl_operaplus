<?php
defined('_JEXEC') or die;

function opSeoText($value, int $limit = 0): string
{
    $value = preg_replace('~</?(?:p|div|li|br|h[1-6]|blockquote|section|article)\b[^>]*>~i', ' ', (string) $value) ?? '';
    $text = html_entity_decode(strip_tags(preg_replace('~<(script|style)\b[^>]*>.*?</\1\s*>~is', ' ', (string) $value) ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    return $limit && mb_strlen($text, 'UTF-8') > $limit ? rtrim(mb_substr($text, 0, $limit - 1, 'UTF-8')) . '…' : $text;
}
function opSeoUrl($value, string $root): string
{
    if (!is_string($value)) {
        return '';
    }
    $value = trim($value);
    if (str_starts_with($value, '{')) {
        $data = json_decode($value, true);
        $value = is_array($data) ? ($data['imagefile'] ?? $data['file'] ?? '') : '';
        if (!is_string($value)) {
            return '';
        }
    }
    $value = trim(explode('#', html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'), 2)[0]);
    if ($value === '' || preg_match('/[\x00-\x20\x7f\\\\<>"\x27]/', $value) || str_starts_with($value, '//')) {
        return '';
    }
    $parts = parse_url($value);
    if (!$parts || isset($parts['user']) || isset($parts['pass'])) {
        return '';
    }
    $path = rawurldecode($parts['path'] ?? '');
    if (preg_match('~(?:^|/)\.{1,2}(?:/|$)~', $path) || preg_match('/[\x00-\x1f\x7f\\\\]/', $path)) {
        return '';
    }
    if (isset($parts['scheme'])) {
        return in_array(strtolower($parts['scheme']), ['http','https'], true) && !empty($parts['host']) ? $value : '';
    }
    if (isset($parts['host']) || empty($parts['path']) || preg_match('~(?:^|/)(?:\.{1,2}|%2e(?:%2e)?)(?:/|$)~i', $parts['path'])) {
        return '';
    }
    if (str_starts_with($value, '/')) {
        $base = parse_url($root);
        return $base['scheme'] . '://' . $base['host'] . (isset($base['port']) ? ':' . $base['port'] : '') . $value;
    }
    return rtrim($root, '/') . '/' . $value;
}
function opSeoSet($doc, string $key, string $value, string $attribute = 'property'): void
{
    if ($value !== '' && !$doc->getMetaData($key, $attribute)) {
        $doc->setMetaData($key, $value, $attribute);
    }
}
function opSeoArticle($doc, $item, string $root): void
{
    if (empty($item->params) || !$item->params->get('access-view', false) || (int) ($item->state ?? 0) !== 1) {
        return;
    }
    $now = gmdate('Y-m-d H:i:s');
    // Joomla may represent an unset date with NULL or the legacy zero date.
    $up = (string) ($item->publish_up ?? '');
    $down = (string) ($item->publish_down ?? '');
    $unset = ['', '0000-00-00 00:00:00'];
    if ((!in_array($up, $unset, true) && $up > $now)
        || (!in_array($down, $unset, true) && $down < $now)) {
        return;
    }
    $images = json_decode((string) ($item->images ?? '{}'), true);
    $images = is_array($images) ? $images : [];
    if (!$doc->getMetaData('og:image','property')) {
        foreach (['fulltext','intro'] as $kind) {
            $image = opSeoUrl($images['image_'.$kind] ?? '', $root);
            if ($image !== '') {
                opSeoSet($doc,'og:image',$image);
                opSeoSet($doc,'og:image:alt',opSeoText($images['image_'.$kind.'_alt'] ?? ''));
                break;
            }
        }
    }
    opSeoSet($doc,'og:type','article');
    opSeoSet($doc,'og:title',opSeoText($doc->getTitle() ?: ($item->title ?? '')));
    if (!$doc->getDescription()) {
        $desc = opSeoText($item->metadesc ?? '') ?: opSeoText($item->introtext ?? '',160);
        if ($desc !== '') {
            $doc->setDescription($desc);
        }
    }
    foreach (['publish_up'=>'article:published_time','modified'=>'article:modified_time'] as $field=>$tag) {
        $date = (string) ($item->{$field} ?? '');
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/D',$date) && substr($date,0,4)!=='0000') {
            $stamp = \DateTimeImmutable::createFromFormat('!Y-m-d H:i:s',$date,new \DateTimeZone('UTC'));
            if ($stamp && $stamp->format('Y-m-d H:i:s') === $date) {
                opSeoSet($doc,$tag,$stamp->format(DATE_ATOM));
            }
        }
    }
}
function opSeoCanonical(string $root,string $request): string
{
    $parts = parse_url($request);
    if (!$parts || empty($parts['path']) || !str_starts_with($parts['path'],'/') || str_starts_with($parts['path'],'//') || isset($parts['host']) || isset($parts['scheme'])) {
        return '';
    }
    $query = [];
    foreach (explode('&',$parts['query'] ?? '') as $pair) {
        $key = strtolower(urldecode(explode('=',$pair,2)[0]));
        $baseKey = preg_replace('/\[.*$/', '', $key);
        // Joomla form tokens are 32 hexadecimal characters; do not publish them.
        $sensitive = preg_match('/^[a-f0-9]{32}$/D', $baseKey)
            || in_array($baseKey, ['token','csrf_token','session','session_id','sessionid','sid','password','passwd','access_token','refresh_token','id_token','authorization','auth','api_key','apikey','secret','code','state','return'], true);
        if ($pair !== '' && !$sensitive && !str_starts_with($key,'utm_') && !in_array($key,['gclid','fbclid','msclkid','dclid','ttclid','mc_cid','mc_eid'],true)) {
            $query[]=$pair;
        }
    }
    return opSeoUrl($parts['path'].($query ? '?'.implode('&',$query) : ''),$root);
}
function opSeoComplete($doc,string $root,string $request,string $brand,string $language,array $images): void
{
    $canonical='';
    foreach ($doc->getHeadData()['links'] ?? [] as $url=>$link) {
        if (($link['relation'] ?? '')==='canonical') {
            $canonical=html_entity_decode((string)$url,ENT_QUOTES|ENT_HTML5,'UTF-8');
            break;
        }
    }
    if ($canonical==='') {
        $canonical=opSeoCanonical($root,$request);
        if ($canonical!=='') {
            $doc->addHeadLink(htmlspecialchars($canonical,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'),'canonical');
        }
    }
    opSeoSet($doc,'og:title',opSeoText($doc->getTitle()));
    opSeoSet($doc,'og:description',opSeoText($doc->getDescription()));
    opSeoSet($doc,'og:type','website');
    opSeoSet($doc,'og:site_name',opSeoText($brand));
    opSeoSet($doc,'og:url',opSeoUrl($canonical,$root));
    if (preg_match('/^[a-z]{2,3}-[A-Z]{2}$/D',$language)) {
        opSeoSet($doc,'og:locale',str_replace('-','_',$language));
    }
    if (!$doc->getMetaData('og:image','property')) {
        foreach ($images as $value) {
            $image=opSeoUrl($value,$root);
            if ($image!=='') {
                opSeoSet($doc,'og:image',$image);
                break;
            }
        }
    }
    opSeoSet($doc,'twitter:card',$doc->getMetaData('og:image','property')?'summary_large_image':'summary','name');
}

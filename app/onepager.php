<?php
declare(strict_types=1);
require_once __DIR__ . '/block_content.php';

function onepagerAnchor(array $page): string
{
    $slug = trim((string)($page['slug'] ?? ''), '/');
    return 'page-' . (preg_replace('/[^a-z0-9-]/', '-', strtolower($slug)) ?: 'home') . '-' . (int)($page['id'] ?? 0);
}

function onepagerBlockHasHeading(array $blocks): bool
{
    $first = $blocks[0] ?? [];
    if (!is_array($first)) return false;
    $data = block_content($first);
    return trim(block_value($data, 'headline')) !== '' || trim(block_value($data, 'title')) !== '';
}

function onepagerPages(array $entries, callable $load, array $currentPage, string $homeSlug, array $selectedIds): array
{
    $pages = [];
    $seen = [];
    $byId = [];
    foreach ($entries as $entry) if (is_array($entry)) $byId[(int)($entry['id'] ?? 0)] = $entry;
    foreach ($selectedIds as $pageId) {
        if (!is_int($pageId) || !isset($byId[$pageId])) continue;
        $entry = $byId[$pageId];
        if (isset($entry['status']) && $entry['status'] !== 'live') continue;
        $slug = trim((string)($entry['slug'] ?? ''), '/');
        $isHome = ($entry['url'] ?? '') === '/' || !empty($entry['is_home']);
        if ($slug === '' && !$isHome) continue;
        $key = (int)($entry['id'] ?? 0) > 0 ? 'id-' . $entry['id'] : 'slug-' . $slug;
        if (isset($seen[$key])) continue;
        $seen[$key] = true;
        $samePage = (int)($entry['id'] ?? 0) > 0 && (int)$entry['id'] === (int)($currentPage['id'] ?? 0);
        $page = $samePage ? $currentPage : $load($slug !== '' ? $slug : $homeSlug);
        if (($page['redirect_type'] ?? 'none') !== 'none') continue;
        $page['is_home'] = $isHome;
        $page['anchor'] = onepagerAnchor($entry);
        $page['form_slug'] = $slug !== '' ? $slug : $homeSlug;
        $page['nav_title'] = (string)($entry['title'] ?? $page['title'] ?? '');
        $pages[] = $page;
    }
    return $pages;
}

function onepagerNavigation(array $items, array $pages): array
{
    $bySlug = [];
    $byId = [];
    foreach ($pages as $page) {
        $bySlug[trim((string)($page['slug'] ?? ''), '/')] = $page;
        if ((int)($page['id'] ?? 0) > 0) $byId[(int)$page['id']] = $page;
    }
    foreach ($items as &$item) {
        if (!is_array($item)) continue;
        if (preg_match('#^(?:https?:)?//#i', (string)($item['url'] ?? ''))) continue;
        if (str_starts_with((string)($item['url'] ?? ''), '#')) continue;
        $slug = trim((string)($item['slug'] ?? parse_url((string)($item['url'] ?? ''), PHP_URL_PATH) ?? ''), '/');
        $target = $byId[(int)($item['id'] ?? 0)] ?? $bySlug[$slug] ?? null;
        if ($target !== null) $item['url'] = '/#' . $target['anchor'];
    }
    unset($item);
    return $items;
}

function onepagerPrepareBlocks(array $blocks, string $pageAnchor, array &$usedIds, int $renderOffset = 0): array
{
    $renamed = [];
    $reserve = static function (string $id, bool $form = false) use (&$usedIds, &$renamed, $pageAnchor): string {
        if ($form) $id = strtolower($id);
        if ($id === '') return '';
        $new = $id;
        for ($suffix = 1; isset($usedIds[$new]); $suffix++) {
            $new = $pageAnchor . '-' . $id . '-' . $suffix;
            if ($form && strlen($new) > 64) $new = 'form-' . substr(hash('sha256', $new), 0, 48);
        }
        $usedIds[$new] = true;
        if ($new !== $id && !isset($renamed[$id])) $renamed[$id] = $new;
        return $new;
    };
    foreach ($blocks as $index => &$block) {
        if (!is_array($block)) continue;
        $nested = is_array($block['data'] ?? null);
        $data = $nested ? $block['data'] : $block;
        if (block_render_type((string)($block['type'] ?? '')) === 'events' && empty($data['anchor'])) $data['anchor'] = 'events';
        if (block_render_type((string)($block['type'] ?? '')) === 'contact_form' && empty($data['form_id'])) $data['form_id'] = 'contact-' . ($renderOffset + $index + 1);
        foreach ($data as $key => &$value) {
            if (is_string($value) && ($key === 'anchor' || str_ends_with($key, '_anchor') || $key === 'form_id')) $value = $reserve($value, $key === 'form_id');
        }
        unset($value);
        if ($nested) $block['data'] = $data; else $block = $data;
    }
    unset($block);
    $rewrite = static function (mixed $value) use (&$rewrite, $renamed): mixed {
        if (is_array($value)) return array_map($rewrite, $value);
        if (is_string($value) && str_starts_with($value, '#') && isset($renamed[substr($value, 1)])) return '#' . $renamed[substr($value, 1)];
        return $value;
    };
    return $rewrite($blocks);
}

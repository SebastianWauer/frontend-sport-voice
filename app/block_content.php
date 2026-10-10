<?php
declare(strict_types=1);

function block_render_type(string $type): string
{
    $types = ['hero', 'logo_strip', 'service_showcase', 'columns', 'events', 'references', 'gallery', 'text', 'contact_form'];
    if (str_starts_with($type, 'sport_voice_') && in_array(substr($type, 12), $types, true)) return substr($type, 12);
    return $type;
}

function block_content(array $block, mixed $data = null): array
{
    return array_merge($block, is_array($block['data'] ?? null) ? $block['data'] : [], is_array($data) ? $data : []);
}

function block_value(array $data, string $key, string $fallback = ''): string
{
    return is_scalar($data[$key] ?? null) ? (string)$data[$key] : $fallback;
}

function block_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function block_lines(string $value): string
{
    return nl2br(block_escape($value));
}

function block_url(string $value, bool $image = false): string
{
    $value = trim($value);
    $pattern = $image ? '#^(https?://|/(?!/))#i' : '#^(https?://|/(?!/)|\#|mailto:|tel:)#i';
    return !preg_match('/[\x00-\x20\\\\]/', $value) && preg_match($pattern, $value) ? $value : '';
}

function block_image(string $value, string $assetBaseUrl = ''): string
{
    $value = block_url($value, true);
    if (str_starts_with($value, '/assets/')) $value = rtrim($assetBaseUrl, '/') . $value;
    return $value;
}

function block_anchor(string $value): string
{
    return preg_replace('/[^a-zA-Z0-9_-]/', '', $value) ?? '';
}

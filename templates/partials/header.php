<?php
require_once __DIR__ . '/../../app/block_content.php';
$assetBaseUrl = isset($assetBaseUrl) && is_string($assetBaseUrl) ? rtrim($assetBaseUrl, '/') : '';
$logoUrl = trim((string)($headerLogoUrl ?? ''));
if ($logoUrl === '') {
    $logoUrl = $assetBaseUrl . '/assets/sport-voice/sport-voice-logo.svg';
}
$items = array_values(array_filter($headerNavItems ?? [], static fn($item): bool => is_array($item)));
$links = [];
foreach ($items as $item) {
    $label = trim((string)($item['title'] ?? $item['nav_label'] ?? ''));
    if ($label === '') {
        continue;
    }
    $url = trim((string)($item['url'] ?? ''));
    if ($url === '') {
        $itemSlug = trim((string)($item['slug'] ?? ''), '/');
        $url = in_array($itemSlug, ['start', 'home'], true) ? '/' : '/' . $itemSlug;
    }
    if (str_starts_with($url, '//') || preg_match('#^https?://#i', $url) === 1) {
        continue;
    }
    if ($url === '' || ($url[0] !== '/' && $url[0] !== '#')) {
        $url = '/' . ltrim($url, '/');
    }
    $links[] = ['label' => $label, 'url' => $url];
}
$sectionLinks = [
    'service_showcase' => 'Leistungen', 'columns' => 'Einsätze',
    'events' => 'Events', 'references' => 'Referenzen', 'text' => 'Sebastian',
];
$templateLinks = [];
foreach (is_array($blocks ?? null) ? $blocks : [] as $pageBlock) {
    if (!is_array($pageBlock)) continue;
    $type = block_render_type((string)($pageBlock['type'] ?? ''));
    if (!isset($sectionLinks[$type])) continue;
    $payload = array_merge($pageBlock, is_array($pageBlock['data'] ?? null) ? $pageBlock['data'] : []);
    $anchor = (string)($payload['anchor'] ?? ($type === 'events' ? 'events' : ''));
    $anchor = preg_replace('/[^a-zA-Z0-9_-]/', '', $anchor) ?? '';
    if ($anchor !== '') $templateLinks[] = ['label' => $sectionLinks[$type], 'url' => '#' . $anchor];
}
if (empty($onePageMode) && $templateLinks !== [] && array_filter($blocks ?? [], static function ($b): bool {
    if (!is_array($b) || block_render_type((string)($b['type'] ?? '')) !== 'hero') return false;
    $payload = array_merge($b, is_array($b['data'] ?? null) ? $b['data'] : []);
    return ($payload['layout'] ?? '') === 'cover';
})) {
    $links = $templateLinks;
}
?>
<a class="skip-link" href="#main">Zum Inhalt</a>
<header class="site-header">
  <div class="header-inner shell">
    <a class="brand" href="/" aria-label="<?= e($siteName ?? 'Sport Voice') ?> - zur Startseite">
      <img src="<?= e($logoUrl) ?>" width="1872" height="562" alt="<?= e($siteName ?? 'Sport Voice') ?>">
    </a>
    <?php if ($links !== []): ?>
      <nav class="desktop-nav" aria-label="Hauptnavigation">
        <?php foreach ($links as $link): ?>
          <a href="<?= e($link['url']) ?>"><?= e($link['label']) ?></a>
        <?php endforeach; ?>
      </nav>
    <?php endif; ?>
    <a class="header-cta" href="<?= e($contactAnchorUrl ?? '#kontakt') ?>">Projekt anfragen <span aria-hidden="true">↗</span></a>
    <button class="menu-toggle" aria-label="Menü öffnen" aria-expanded="false" aria-controls="mobile-nav"><span></span><span></span></button>
  </div>
  <nav id="mobile-nav" class="mobile-nav" aria-label="Mobile Navigation" hidden>
    <?php foreach ($links as $link): ?>
      <a href="<?= e($link['url']) ?>"><?= e($link['label']) ?> <span aria-hidden="true">↗</span></a>
    <?php endforeach; ?>
    <a href="<?= e($contactAnchorUrl ?? '#kontakt') ?>">Projekt anfragen <span aria-hidden="true">↗</span></a>
  </nav>
</header>

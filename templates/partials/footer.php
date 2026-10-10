<?php
$assetBaseUrl = isset($assetBaseUrl) && is_string($assetBaseUrl) ? rtrim($assetBaseUrl, '/') : '';
$logoUrl = $assetBaseUrl . '/assets/sport-voice/sport-voice-logo.svg';
$footerLinks = array_values(array_filter($footerNavItems ?? [], static fn($item): bool => is_array($item)));
?>
<footer class="site-footer">
  <div class="shell footer-main">
    <a class="brand footer-brand" href="#main">
      <img src="<?= e($logoUrl) ?>" width="1872" height="562" alt="<?= e($siteName ?? 'Sport Voice') ?>">
    </a>
    <p>Moderator · Kommentator · Pressedienste</p>
    <a class="back-top" href="#main">Nach oben <span aria-hidden="true">↑</span></a>
  </div>
  <div class="shell footer-bottom">
    <span>© <span id="year"><?= date('Y') ?></span> <?= e($siteName ?? 'Sport Voice') ?></span>
    <div>
      <?php foreach ($footerLinks as $item): ?>
        <?php
          $label = trim((string)($item['title'] ?? $item['nav_label'] ?? 'Link'));
          $url = trim((string)($item['url'] ?? ''));
          if ($url === '') {
              $itemSlug = trim((string)($item['slug'] ?? ''), '/');
              $url = in_array($itemSlug, ['start', 'home'], true) ? '/' : '/' . $itemSlug;
          }
        ?>
        <a href="<?= e($url) ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
      <a href="https://www.facebook.com/WauerSportVoice/" target="_blank" rel="noopener noreferrer">Facebook ↗</a>
    </div>
  </div>
</footer>

<?php
require_once __DIR__ . '/../../../app/block_content.php';
$p = block_content($block ?? [], $data ?? null);
$count = max(1, min(8, (int)block_value($p, 'item_count', '5')));
$logos = [];
for ($i = 1; $i <= $count; $i++) {
    $image = block_image(block_value($p, "item_{$i}_image_url"), (string)($assetBaseUrl ?? ''));
    if ($image === '') continue;
    $imagePath = (string)(parse_url($image, PHP_URL_PATH) ?? '');
    if (str_ends_with($imagePath, '/media/file') || str_ends_with($imagePath, '/media/thumb')) {
        $parts = explode('#', $image, 2);
        $image = $parts[0] . (str_contains($parts[0], '?') ? '&' : '?') . 'sv_logo_rev=' . (int)filemtime(__FILE__);
        if (isset($parts[1])) $image .= '#' . $parts[1];
    }
    $logos[] = ['image' => $image, 'title' => block_value($p, "item_{$i}_title"), 'url' => block_url(block_value($p, "item_{$i}_link_url")), 'width' => max(60, min(240, (int)block_value($p, "item_{$i}_width", '160')))];
}
if ($logos === []) return;
?>
<section class="reference-strip" aria-label="<?= block_escape(block_value($p, 'caption', 'Referenzen')) ?>">
  <div class="shell strip-inner">
    <p class="strip-caption"><?= block_lines(block_value($p, 'caption')) ?></p>
    <div class="reference-logos" style="--logo-count:<?= count($logos) ?>">
      <?php foreach ($logos as $logo): ?>
        <?php if ($logo['url'] !== ''): ?><a class="reference-logo" href="<?= block_escape($logo['url']) ?>"><?php else: ?><span class="reference-logo"><?php endif; ?>
          <img src="<?= block_escape($logo['image']) ?>" alt="<?= block_escape($logo['title']) ?>" style="max-width:<?= $logo['width'] ?>px">
        <?php if ($logo['url'] !== ''): ?></a><?php else: ?></span><?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

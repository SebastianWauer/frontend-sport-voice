<?php
require_once __DIR__ . '/../../../app/block_content.php';
$p = block_content($block ?? [], $data ?? null);
$items = json_decode(block_value($p, 'items_json', '[]'), true);
$items = is_array($items) ? array_slice($items, 0, 20) : [];
?>
<section class="work gallery-editorial section"><div class="shell">
  <?php if (block_value($p, 'headline') !== ''): ?><h2><?= block_lines(block_value($p, 'headline')) ?></h2><?php endif; ?>
  <div class="on-site-gallery" style="--gallery-cols:<?= max(2, min(4, (int)block_value($p, 'cols', '2'))) ?>">
  <?php foreach ($items as $item):
      if (!is_array($item)) continue;
      $image = block_image(block_value($item, 'url'), (string)($assetBaseUrl ?? ''));
      if ($image === '') continue;
      $caption = [block_value($item, 'caption'), block_value($item, 'caption_secondary')];
  ?>
    <figure><button class="photo-button" type="button" data-image="<?= block_escape($image) ?>" data-caption="<?= block_escape(block_value($item, 'alt')) ?>" aria-label="Foto vergroessern"><img src="<?= block_escape($image) ?>" alt="<?= block_escape(block_value($item, 'alt')) ?>" loading="lazy"><span class="photo-expand" aria-hidden="true">&#8599;</span></button><?php if (trim(implode('', $caption)) !== ''): ?><figcaption><span><?= block_escape(trim($caption[0])) ?></span><?php if (isset($caption[1])): ?><span><?= block_escape(trim($caption[1])) ?></span><?php endif; ?></figcaption><?php endif; ?></figure>
  <?php endforeach; ?>
  </div>
</div></section>

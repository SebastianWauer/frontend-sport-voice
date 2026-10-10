<?php
require_once __DIR__ . '/../../../app/block_content.php';
$p = block_content($block ?? [], $data ?? null);
$image = block_image(block_value($p, 'image_url'), (string)($assetBaseUrl ?? ''));
$anchor = block_anchor(block_value($p, 'anchor'));
?>
<section<?= $anchor !== '' ? ' id="' . block_escape($anchor) . '"' : '' ?> class="hero">
  <?php if ($image !== ''): ?><img class="hero-photo" src="<?= block_escape($image) ?>" alt="<?= block_escape(block_value($p, 'image_alt')) ?>" fetchpriority="high"><?php endif; ?>
  <div class="hero-shade"></div>
  <div class="hero-content shell">
    <?php if (block_value($p, 'topline') !== ''): ?><div class="eyebrow light"><span class="red-dot" aria-hidden="true"></span><?= block_escape(block_value($p, 'topline')) ?></div><?php endif; ?>
    <h1><?= block_lines(block_value($p, 'headline')) ?><?php if (block_value($p, 'headline_accent') !== ''): ?> <span><?= block_lines(block_value($p, 'headline_accent')) ?></span><?php endif; ?></h1>
    <?php if (block_value($p, 'subtitle') !== ''): ?><p class="hero-description"><?= block_lines(block_value($p, 'subtitle')) ?></p><?php endif; ?>
    <div class="hero-actions">
      <?php if (($url = block_url(block_value($p, 'button_url'))) !== '' && block_value($p, 'button_text') !== ''): ?><a class="button button-red" href="<?= block_escape($url) ?>"><?= block_escape(block_value($p, 'button_text')) ?> <span aria-hidden="true">&#8599;</span></a><?php endif; ?>
      <?php if (($url = block_url(block_value($p, 'button_secondary_url'))) !== '' && block_value($p, 'button_secondary_text') !== ''): ?><a class="hero-link" href="<?= block_escape($url) ?>"><?= block_escape(block_value($p, 'button_secondary_text')) ?> <span aria-hidden="true">&#8595;</span></a><?php endif; ?>
    </div>
    <?php if (block_value($p, 'meta_left') !== '' || block_value($p, 'meta_right') !== ''): ?><div class="hero-bottom"><p><?= block_escape(block_value($p, 'meta_left')) ?></p><span><?= block_escape(block_value($p, 'meta_right')) ?></span></div><?php endif; ?>
  </div>
  <?php if (block_value($p, 'image_caption') !== ''): ?><span class="hero-photo-label"><?= block_escape(block_value($p, 'image_caption')) ?></span><?php endif; ?>
</section>

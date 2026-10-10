<?php
require_once __DIR__ . '/../../../app/block_content.php';
$p = block_content($block ?? [], $data ?? null);
$anchor = block_anchor(block_value($p, 'anchor'));
$image = block_image(block_value($p, 'image_url'), (string)($assetBaseUrl ?? ''));
$paragraphs = preg_split('/\r?\n\s*\r?\n/', block_value($p, 'text')) ?: [];
?>
<section<?= $anchor !== '' ? ' id="' . block_escape($anchor) . '"' : '' ?> class="about section shell<?= $image === '' ? ' about--no-image' : '' ?>">
  <?php if ($image !== ''): ?><div class="about-portrait"><button class="photo-button" type="button" data-image="<?= block_escape($image) ?>" data-caption="<?= block_escape(block_value($p, 'image_alt')) ?>" aria-label="Portraet vergroessern"><img src="<?= block_escape($image) ?>" alt="<?= block_escape(block_value($p, 'image_alt')) ?>" loading="lazy"><span class="photo-expand" aria-hidden="true">&#8599;</span></button><div class="portrait-caption"><span><?= block_escape(block_value($p, 'image_caption')) ?></span><span><?= block_escape(block_value($p, 'image_credit')) ?></span></div></div><?php endif; ?>
  <div class="about-copy">
    <?php if (block_value($p, 'topline') !== ''): ?><p class="eyebrow"><span class="section-number"><?= block_escape(block_value($p, 'section_number')) ?></span><?= block_escape(block_value($p, 'topline')) ?></p><?php endif; ?>
    <h2><?= block_lines(block_value($p, 'title')) ?><?php if (block_value($p, 'title_accent') !== ''): ?><br><span class="muted"><?= block_lines(block_value($p, 'title_accent')) ?></span><?php endif; ?></h2>
    <?php if (block_value($p, 'subtitle') !== ''): ?><p><?= block_lines(block_value($p, 'subtitle')) ?></p><?php endif; ?>
    <?php if (block_value($p, 'intro') !== ''): ?><p><strong><?= block_lines(block_value($p, 'intro')) ?></strong></p><?php endif; ?>
    <?php if (preg_match('/<[^>]+>/', block_value($p, 'text')) === 1): ?><?= $text ?? block_escape(block_value($p, 'text')) ?><?php else: ?>
    <?php foreach ($paragraphs as $paragraph): if (trim($paragraph) === '') continue; ?><p><?= block_lines($paragraph) ?></p><?php endforeach; ?>
    <?php endif; ?>
    <?php $pointCount = max(0, min(6, (int)block_value($p, 'point_count', '0'))); if ($pointCount): ?><div class="principles"><?php for ($i = 1; $i <= $pointCount; $i++): ?><div><span><?= sprintf('%02d', $i) ?></span><strong><?= block_escape(block_value($p, "point_{$i}_title")) ?></strong><p><?= block_lines(block_value($p, "point_{$i}_text")) ?></p></div><?php endfor; ?></div><?php endif; ?>
    <?php if (($url = block_url(block_value($p, 'button_url'))) !== '' && block_value($p, 'button_text') !== ''): ?><a class="text-link" href="<?= block_escape($url) ?>"><?= block_escape(block_value($p, 'button_text')) ?> <span aria-hidden="true">&#8599;</span></a><?php endif; ?>
  </div>
</section>

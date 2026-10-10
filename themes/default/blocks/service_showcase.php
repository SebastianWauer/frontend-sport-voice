<?php
require_once __DIR__ . '/../../../app/block_content.php';
$p = block_content($block ?? [], $data ?? null);
$isList = block_value($p, 'layout', 'showcase') === 'list';
$count = max(1, min(6, (int)block_value($p, 'item_count', '3')));
$anchor = block_anchor(block_value($p, 'anchor'));
$headingTag = $isList ? 'h2' : 'h1';
$icons = [
    'microphone' => '<svg class="service-icon" viewBox="0 0 32 32" fill="none" aria-hidden="true"><rect x="11" y="3" width="10" height="17" rx="5"/><path d="M6 15v2a10 10 0 0 0 20 0v-2M16 27v4M10 31h12"/></svg>',
    'video' => '<svg class="service-icon" viewBox="0 0 32 32" fill="none" aria-hidden="true"><rect x="3" y="7" width="18" height="18" rx="2"/><path d="m21 13 8-5v16l-8-5M9 12v8l7-4z"/></svg>',
    'file' => '<svg class="service-icon" viewBox="0 0 32 32" fill="none" aria-hidden="true"><path d="M7 3h13l6 6v20H7zM20 3v7h6M12 15h9M12 20h9M12 25h5"/></svg>',
];
?>
<section<?= $anchor !== '' ? ' id="' . block_escape($anchor) . '"' : '' ?> class="<?= $isList ? 'services' : 'service-showcase' ?> section shell">
  <div class="section-intro">
    <?php if (block_value($p, 'kicker') !== ''): ?><p class="eyebrow"><span class="section-number"><?= block_escape(block_value($p, 'section_number')) ?></span><?= block_escape(block_value($p, 'kicker')) ?></p><?php endif; ?>
    <<?= $headingTag ?>><?= block_lines(block_value($p, 'headline')) ?><?php if (block_value($p, 'headline_muted') !== ''): ?><br><span class="muted"><?= block_lines(block_value($p, 'headline_muted')) ?></span><?php endif; ?></<?= $headingTag ?>>
    <p><?= block_lines(block_value($p, 'lead')) ?></p>
    <?php if (($url = block_url(block_value($p, 'button_url'))) !== '' && block_value($p, 'button_text') !== ''): ?><a class="text-link" href="<?= block_escape($url) ?>"><?= block_escape(block_value($p, 'button_text')) ?> <span aria-hidden="true">&#8599;</span></a><?php endif; ?>
    <?php if (!$isList && block_value($p, 'stock_items') !== ''): ?><p class="showcase-stock"><?= block_escape(block_value($p, 'stock_label')) ?>: <?= block_escape(block_value($p, 'stock_items')) ?></p><?php endif; ?>
  </div>
  <div class="<?= $isList ? 'service-list' : 'showcase-list' ?>">
  <?php for ($i = 1; $i <= $count; $i++):
      $title = block_value($p, "item_{$i}_title");
      if ($title === '') continue;
      $image = block_image(block_value($p, "item_{$i}_image_url"), (string)($assetBaseUrl ?? ''));
  ?>
    <article class="<?= $isList ? 'service-row' : 'showcase-item' ?>">
      <?php if ($isList): ?><span class="service-index"><?= sprintf('%02d', $i) ?> /</span><?php elseif ($image !== ''): ?><img src="<?= block_escape($image) ?>" alt="<?= block_escape($title) ?>" loading="lazy"><?php endif; ?>
      <div><h3><?= block_escape($title) ?></h3>
        <?php if (block_value($p, "item_{$i}_lead") !== ''): ?><p><strong><?= block_lines(block_value($p, "item_{$i}_lead")) ?></strong></p><?php endif; ?>
        <p><?= block_lines(block_value($p, "item_{$i}_text")) ?></p>
        <?php $tags = array_filter(array_map('trim', explode(',', block_value($p, "item_{$i}_tags")))); if ($tags !== []): ?><div class="tags"><?php foreach ($tags as $tag): ?><span><?= block_escape($tag) ?></span><?php endforeach; ?></div><?php endif; ?>
        <?php if (($url = block_url(block_value($p, "item_{$i}_link_url"))) !== '' && block_value($p, "item_{$i}_link_text") !== ''): ?><a class="text-link" href="<?= block_escape($url) ?>"><?= block_escape(block_value($p, "item_{$i}_link_text")) ?> <span aria-hidden="true">&#8599;</span></a><?php endif; ?>
      </div>
      <?php if ($isList): ?><?= $icons[block_value($p, "item_{$i}_icon")] ?? '' ?><?php endif; ?>
    </article>
  <?php endfor; ?>
  </div>
  <?php if (block_value($p, 'closing_headline') !== ''): ?>
  <div class="showcase-closing">
    <p class="eyebrow"><?= block_escape(block_value($p, 'closing_kicker')) ?></p><h2><?= block_lines(block_value($p, 'closing_headline')) ?></h2><p><?= block_lines(block_value($p, 'closing_text')) ?></p>
    <?php foreach (['closing_button' => 'button button-red', 'closing_secondary' => 'text-link'] as $prefix => $class):
        $text = block_value($p, $prefix . '_text'); $url = block_url(block_value($p, $prefix . '_url'));
        if ($text === '' || $url === '') continue;
    ?><a class="<?= $class ?>" href="<?= block_escape($url) ?>"><?= block_escape($text) ?> <span aria-hidden="true">&#8599;</span></a><?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>

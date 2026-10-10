<?php
require_once __DIR__ . '/../../../app/block_content.php';
$p = block_content($block ?? [], $data ?? null);
$anchor = block_anchor(block_value($p, 'anchor'));
$count = max(1, min(12, (int)block_value($p, 'item_count', '4')));
$items = [];
$categories = [];
for ($i = 1; $i <= $count; $i++) {
    $title = block_value($p, "item_{$i}_title");
    if ($title === '') continue;
    $category = trim(block_value($p, "item_{$i}_category"));
    if ($category !== '' && !in_array($category, $categories, true)) $categories[] = $category;
    $items[] = ['title' => $title, 'text' => block_value($p, "item_{$i}_text"), 'image' => block_image(block_value($p, "item_{$i}_image_url"), (string)($assetBaseUrl ?? '')), 'alt' => block_value($p, "item_{$i}_image_alt", $title), 'category' => $category, 'meta' => block_value($p, "item_{$i}_meta"), 'url' => block_url(block_value($p, "item_{$i}_link_url")), 'anchor' => block_anchor(block_value($p, "item_{$i}_anchor"))];
}
$featureImage = block_image(block_value($p, 'feature_image_url'), (string)($assetBaseUrl ?? ''));
?>
<section<?= $anchor !== '' ? ' id="' . block_escape($anchor) . '"' : '' ?> class="work references-section section">
  <div class="shell">
    <div class="section-heading"><div>
      <?php if (block_value($p, 'topline') !== ''): ?><p class="eyebrow"><span class="section-number"><?= block_escape(block_value($p, 'section_number')) ?></span><?= block_escape(block_value($p, 'topline')) ?></p><?php endif; ?>
      <?php if (block_value($p, 'headline') !== ''): ?><h2><?= block_lines(block_value($p, 'headline')) ?></h2><?php endif; ?>
    </div><?php if (block_value($p, 'intro') !== ''): ?><p><?= block_lines(block_value($p, 'intro')) ?></p><?php endif; ?></div>
    <?php if (block_value($p, 'show_feature') === '1' && block_value($p, 'feature_title') !== ''): ?>
    <div<?= ($id = block_anchor(block_value($p, 'feature_anchor'))) !== '' ? ' id="' . block_escape($id) . '"' : '' ?> class="work-feature">
      <?php if ($featureImage !== ''): ?><button class="photo-button" type="button" data-image="<?= block_escape($featureImage) ?>" data-caption="<?= block_escape(block_value($p, 'feature_image_alt')) ?>" aria-label="Foto vergroessern"><img src="<?= block_escape($featureImage) ?>" alt="<?= block_escape(block_value($p, 'feature_image_alt')) ?>" loading="lazy"><span class="photo-expand" aria-hidden="true">&#8599;</span><?php if (block_value($p, 'feature_caption') !== ''): ?><span class="image-corner"><?= block_escape(block_value($p, 'feature_caption')) ?></span><?php endif; ?></button><?php endif; ?>
      <article class="work-feature-copy">
        <div class="eyebrow"><?= block_escape(block_value($p, 'feature_topline')) ?></div>
        <h3><?= block_lines(block_value($p, 'feature_title')) ?></h3><p><?= block_lines(block_value($p, 'feature_text')) ?></p>
        <?php for ($i = 1; $i <= 2; $i++): if (block_value($p, "feature_detail_{$i}_text") === '') continue; ?><div class="work-detail"><span><?= block_escape(block_value($p, "feature_detail_{$i}_label")) ?></span><strong><?= block_escape(block_value($p, "feature_detail_{$i}_text")) ?></strong></div><?php endfor; ?>
        <?php if (($url = block_url(block_value($p, 'feature_link_url'))) !== '' && block_value($p, 'feature_link_text') !== ''): ?><a class="text-link" href="<?= block_escape($url) ?>"><?= block_escape(block_value($p, 'feature_link_text')) ?> <span aria-hidden="true">&#8599;</span></a><?php endif; ?>
      </article>
    </div>
    <?php endif; ?>
    <?php if (block_value($p, 'show_filters', '1') === '1' && count($categories) > 1): ?>
    <div class="work-filters" role="group" aria-label="Referenzen filtern"><span><?= block_escape(block_value($p, 'filter_label')) ?></span><button type="button" class="filter-button active" data-filter="all" aria-pressed="true">Alle</button><?php foreach ($categories as $category): ?><button type="button" class="filter-button" data-filter="<?= block_escape($category) ?>" aria-pressed="false"><?= block_escape($category) ?></button><?php endforeach; ?></div>
    <?php endif; ?>
    <div class="reference-list" aria-live="polite" aria-atomic="false">
      <?php foreach ($items as $item): ?>
      <article<?= $item['anchor'] !== '' ? ' id="' . block_escape($item['anchor']) . '"' : '' ?> class="reference-row" data-category="<?= block_escape($item['category']) ?>">
        <span class="reference-mark"><?php if ($item['image'] !== ''): ?><img src="<?= block_escape($item['image']) ?>" alt="<?= block_escape($item['alt']) ?>" loading="lazy"><?php endif; ?></span>
        <div><h3><?php if ($item['url'] !== ''): ?><a href="<?= block_escape($item['url']) ?>"><?php endif; ?><?= block_escape($item['title']) ?><?php if ($item['url'] !== ''): ?></a><?php endif; ?></h3><p><?= block_lines($item['text']) ?></p></div>
        <span class="reference-type"><?= block_escape($item['meta']) ?></span><?php if ($item['url'] !== ''): ?><a class="reference-arrow" href="<?= block_escape($item['url']) ?>" aria-label="<?= block_escape($item['title']) ?>">&#8599;</a><?php else: ?><span class="reference-arrow" aria-hidden="true">&#8599;</span><?php endif; ?>
      </article>
      <?php endforeach; ?>
    </div>
    <?php if (block_value($p, 'footnote') !== ''): ?><p class="references-footnote"><?= block_lines(block_value($p, 'footnote')) ?></p><?php endif; ?>
  </div>
</section>

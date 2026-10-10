<?php
require_once __DIR__ . '/../../../app/block_content.php';
$p = block_content($block ?? [], $data ?? null);
$count = max(1, min(6, (int)block_value($p, 'col_count', '2')));
$anchor = block_anchor(block_value($p, 'anchor'));
?>
<section<?= $anchor !== '' ? ' id="' . block_escape($anchor) . '"' : '' ?> class="season section">
  <div class="shell">
    <div class="section-heading"><div>
      <?php if (block_value($p, 'topline') !== ''): ?><p class="eyebrow"><span class="section-number"><?= block_escape(block_value($p, 'section_number')) ?></span><?= block_escape(block_value($p, 'topline')) ?></p><?php endif; ?>
      <?php if (block_value($p, 'title') !== ''): ?><h2><?= block_lines(block_value($p, 'title')) ?></h2><?php endif; ?>
    </div><?php if (block_value($p, 'intro') !== ''): ?><p><?= block_lines(block_value($p, 'intro')) ?></p><?php endif; ?></div>
    <ul class="season-grid">
    <?php for ($i = 1; $i <= $count; $i++):
        $title = block_value($p, "col_{$i}_title");
        if ($title === '' && block_value($p, "col_{$i}_text") === '') continue;
    ?>
      <li class="season-item">
        <div class="season-item-top"><span><?= block_escape(block_value($p, "col_{$i}_category")) ?></span><span><?= block_escape(block_value($p, "col_{$i}_meta")) ?></span></div>
        <h3><?= block_escape($title) ?></h3>
        <p><?= block_lines(block_value($p, "col_{$i}_text")) ?></p>
        <?php if (block_value($p, "col_{$i}_note") !== ''): ?><p class="season-scope"><?= block_lines(block_value($p, "col_{$i}_note")) ?></p><?php endif; ?>
        <?php if (($url = block_url(block_value($p, "col_{$i}_link_url"))) !== '' && block_value($p, "col_{$i}_button_show") === '1'): ?><a class="text-link" href="<?= block_escape($url) ?>"><?= block_escape(block_value($p, "col_{$i}_button_text", 'Mehr')) ?> <span aria-hidden="true">&#8599;</span></a><?php endif; ?>
      </li>
    <?php endfor; ?>
    </ul>
  </div>
</section>

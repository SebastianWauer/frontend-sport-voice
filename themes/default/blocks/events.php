<?php
/** @var array $block */
/** @var array|null $data */

$p = [];
if (isset($data) && is_array($data)) {
    $p = $data;
} elseif (isset($block['data']) && is_array($block['data'])) {
    $p = array_merge($block, $block['data']);
} elseif (is_array($block)) {
    $p = $block;
}

$headline = trim((string)($p['headline'] ?? 'Events & Termine'));
$intro = trim((string)($p['intro'] ?? ''));
$items = is_array($p['items'] ?? null) ? $p['items'] : [];
$showPast = ((string)($p['include_past'] ?? '1') !== '0');
$nowDate = date('Y-m-d');

$monthMap = [
    '01' => 'JAN', '02' => 'FEB', '03' => 'MAR', '04' => 'APR',
    '05' => 'MAI', '06' => 'JUN', '07' => 'JUL', '08' => 'AUG',
    '09' => 'SEP', '10' => 'OKT', '11' => 'NOV', '12' => 'DEZ',
];
$formatDate = static function (string $ymd) use ($monthMap): string {
    if ($ymd === '' || preg_match('/^\d{4}-\d{2}-\d{2}$/', $ymd) !== 1) {
        return '';
    }
    return substr($ymd, 8, 2) . '. ' . ($monthMap[substr($ymd, 5, 2)] ?? substr($ymd, 5, 2)) . ' ' . substr($ymd, 0, 4);
};

$events = [];
foreach ($items as $item) {
    if (!is_array($item)) {
        continue;
    }
    $dateFrom = trim((string)($item['date_from'] ?? ''));
    $dateTo = trim((string)($item['date_to'] ?? ''));
    $dateRaw = trim((string)($item['date'] ?? ''));
    if ($dateFrom === '' && $dateRaw !== '') {
        $ts = strtotime($dateRaw);
        $dateFrom = $ts !== false ? date('Y-m-d', $ts) : '';
    }
    if ($dateFrom === '' && $dateTo !== '') {
        $dateFrom = $dateTo;
    }
    $cmp = $dateTo !== '' ? $dateTo : $dateFrom;
    $isPast = $cmp !== '' && $cmp < $nowDate;
    if ($isPast && !$showPast) {
        continue;
    }
    $categoryNames = is_array($item['category_names'] ?? null)
        ? array_values(array_filter(array_map(static fn($v): string => trim((string)$v), $item['category_names']), static fn(string $v): bool => $v !== ''))
        : [];
    if ($categoryNames === []) {
        $singleName = trim((string)($item['category_name'] ?? ''));
        if ($singleName !== '') {
            $categoryNames = array_values(array_filter(array_map('trim', explode(',', $singleName)), static fn(string $v): bool => $v !== ''));
        }
    }
    $dateLabel = $formatDate($dateFrom);
    if ($dateTo !== '' && $dateTo !== $dateFrom) {
        $dateToLabel = $formatDate($dateTo);
        if ($dateToLabel !== '') {
            $dateLabel .= $dateLabel !== '' ? ' - ' . $dateToLabel : $dateToLabel;
        }
    }
    $events[] = [
        'title' => trim((string)($item['title'] ?? 'Event')),
        'subtitle' => trim((string)($item['subtitle'] ?? '')),
        'text' => trim((string)($item['text'] ?? '')),
        'date_from' => $dateFrom,
        'date_label' => $dateLabel,
        'category' => implode(' / ', $categoryNames),
        'is_past' => $isPast,
        'youtube_url' => trim((string)($item['youtube_url'] ?? '')),
    ];
}

usort($events, static function (array $a, array $b): int {
    if ($a['is_past'] !== $b['is_past']) {
        return $a['is_past'] ? 1 : -1;
    }
    if ($a['date_from'] === $b['date_from']) {
        return strcmp($a['title'], $b['title']);
    }
    return $a['is_past'] ? strcmp($b['date_from'], $a['date_from']) : strcmp($a['date_from'], $b['date_from']);
});

if ($headline === '' && $intro === '' && $events === []) {
    return;
}
?>
<section class="block block-events season section" id="einsaetze">
  <div class="shell">
    <div class="section-heading">
      <div>
        <p class="eyebrow"><span class="section-number">02</span> TERMINE</p>
        <?php if ($headline !== ''): ?>
          <h2><?= htmlspecialchars($headline, ENT_QUOTES, 'UTF-8') ?></h2>
        <?php endif; ?>
      </div>
      <?php if ($intro !== ''): ?>
        <p><?= nl2br(htmlspecialchars($intro, ENT_QUOTES, 'UTF-8')) ?></p>
      <?php endif; ?>
    </div>

    <?php if ($events === []): ?>
      <p class="sport-events-empty">Aktuell sind keine Events veröffentlicht.</p>
    <?php else: ?>
      <ul class="season-grid sport-events-grid">
        <?php foreach ($events as $event): ?>
          <li class="season-item sport-event<?= $event['is_past'] ? ' is-past' : '' ?>">
            <div class="season-item-top">
              <span><?= htmlspecialchars($event['category'] !== '' ? $event['category'] : 'EVENT', ENT_QUOTES, 'UTF-8') ?></span>
              <span><?= htmlspecialchars($event['date_label'] !== '' ? $event['date_label'] : ($event['is_past'] ? 'VERGANGEN' : 'TERMIN OFFEN'), ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <h3><?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?></h3>
            <?php if ($event['subtitle'] !== ''): ?>
              <p class="sport-event-subtitle"><?= htmlspecialchars($event['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <?php if ($event['text'] !== ''): ?>
              <p><?= nl2br(htmlspecialchars($event['text'], ENT_QUOTES, 'UTF-8')) ?></p>
            <?php endif; ?>
            <?php if ($event['youtube_url'] !== ''): ?>
              <a class="text-link sport-event-link" href="<?= htmlspecialchars($event['youtube_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Stream ansehen <span aria-hidden="true">↗</span></a>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</section>

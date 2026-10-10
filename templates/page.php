<?php
require_once __DIR__ . '/../app/block_content.php';
$pageTitle = (string)($pageTitle ?? 'Seite');
$pageSubtitle = trim((string)($pageSubtitle ?? ''));
$blocksList = is_array($blocks ?? null) ? $blocks : [];
$contactFormStates = is_array($contactFormStates ?? null) ? $contactFormStates : [];
$contactTurnstileSiteKey = (isset($contactTurnstileSiteKey) && is_string($contactTurnstileSiteKey)) ? trim($contactTurnstileSiteKey) : '';
$publicSettings = is_array($publicSettings ?? null) ? $publicSettings : [];
$client = $client ?? null; // nicht jeder Render-Pfad reicht ihn durch (z.B. Fehlerseiten)

$currentSlug = trim((string)($slug ?? ''), '/');
$assetBaseUrl = isset($assetBaseUrl) && is_string($assetBaseUrl) ? $assetBaseUrl : '';

if (!function_exists('render_page_blocks')) {
    /**
     * @param array<int,mixed> $blocksList
     * @param array<string,mixed> $context
     */
    function render_page_blocks(array $blocksList, array $context = []): void
    {
        foreach ($blocksList as $blockIndex => $block) {
            if (!is_array($block)) {
                continue;
            }
            $block['_render_index'] = (int)$blockIndex;
            $type = block_render_type((string)($block['type'] ?? ''));
            $data = $block['data'] ?? [];
            if (!is_array($data)) {
                $data = [];
            }

            $flatData = $block;
            unset($flatData['type'], $flatData['data']);
            if (is_array($flatData)) {
                $data = array_merge($flatData, $data);
            }

            $template = match ($type) {
                'text'    => 'themes/default/blocks/text.php',
                'hero'    => 'themes/default/blocks/hero.php',
                'image'   => 'themes/default/blocks/image.php',
                'columns' => 'themes/default/blocks/columns.php',
                'cta'     => 'themes/default/blocks/cta.php',
                'faq'     => 'themes/default/blocks/faq.php',
                'gallery' => 'themes/default/blocks/gallery.php',
                'video'   => 'themes/default/blocks/video.php',
                'contact_form' => 'themes/default/blocks/contact_form.php',
                'imprint' => 'themes/default/blocks/imprint.php',
                'events' => 'themes/default/blocks/events.php',
                'news' => 'themes/default/blocks/news.php',
                'three_columns_layout' => 'themes/default/blocks/three_columns_layout.php',
                'social_account' => 'themes/default/blocks/social_account.php',
                'service_showcase' => 'themes/default/blocks/service_showcase.php',
                'logo_strip' => 'themes/default/blocks/logo_strip.php',
                'references' => 'themes/default/blocks/references.php',
                default   => 'templates/blocks/unknown.php',
            };

            render($template, array_merge($context, compact('block', 'data')));
        }
    }
}

$hasHero = false;
foreach ($blocksList as $candidateBlock) {
    if (!is_array($candidateBlock)) {
        continue;
    }
    $candidateData = array_merge($candidateBlock, is_array($candidateBlock['data'] ?? null) ? $candidateBlock['data'] : []);
    $candidateType = block_render_type((string)($candidateBlock['type'] ?? ''));
    if ($candidateType === 'hero'
        || ($candidateType === 'service_showcase' && ($candidateData['layout'] ?? 'showcase') !== 'list')) {
        $hasHero = true;
        break;
    }
}
?>
<?php
if (!$hasHero):
?>
<section class="page-headline-wrap">
    <h1 class="page-title"><?php echo e($pageTitle); ?></h1>
    <?php if ($pageSubtitle !== ''): ?>
    <p class="page-subtitle"><?php echo e($pageSubtitle); ?></p>
    <?php endif; ?>
</section>
<?php
endif;

render_page_blocks($blocksList, compact('contactFormStates', 'currentSlug', 'contactTurnstileSiteKey', 'publicSettings', 'client', 'assetBaseUrl'));
?>

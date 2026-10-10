<?php require_once __DIR__ . '/../app/block_content.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $client = $client ?? null; // nicht jeder Render-Pfad reicht ihn durch (z.B. Fehlerseiten)
    $brandVersion = @filemtime(__DIR__ . '/../assets/css/brand.php') ?: time();
    $themeVersion = @filemtime(__DIR__ . '/../assets/css/theme.css') ?: time();
    $sportVoiceVersion = @filemtime(__DIR__ . '/../assets/css/sport-voice.css') ?: time();
    $sportVoiceJsVersion = @filemtime(__DIR__ . '/../assets/js/sport-voice.js') ?: time();
    $assetBaseUrl = (isset($assetBaseUrl) && is_string($assetBaseUrl)) ? rtrim($assetBaseUrl, '/') : '';
    $faviconUrl = (isset($faviconUrl) && is_string($faviconUrl)) ? trim($faviconUrl) : '';
    $headerLogoUrl = (isset($headerLogoUrl) && is_string($headerLogoUrl)) ? trim($headerLogoUrl) : '';
    $previewMainOnly = !empty($previewMainOnly);
    $usesEditorialBlocks = false;
    $needsLegacyStyles = false;
    foreach (is_array($blocks ?? null) ? $blocks : [] as $candidate) {
        if (!is_array($candidate)) continue;
        $candidateType = block_render_type((string)($candidate['type'] ?? ''));
        $payload = array_merge($candidate, is_array($candidate['data'] ?? null) ? $candidate['data'] : []);
        $layout = (string)($payload['layout'] ?? '');
        $modernLayout = match ($candidateType) {
            'hero' => 'cover', 'service_showcase' => 'list', 'columns', 'gallery' => 'editorial',
            'text' => 'portrait', 'contact_form' => 'split', default => null
        };
        if (in_array($candidateType, ['logo_strip','references'], true) || ($modernLayout !== null && $layout === $modernLayout)) {
            $usesEditorialBlocks = true;
            if ($candidateType === 'contact_form' && ($payload['delivery'] ?? 'server') === 'server') $needsLegacyStyles = true;
        } elseif ($candidateType !== 'events') {
            $needsLegacyStyles = true;
        }
    }
    $contactFormStates = is_array($contactFormStates ?? null) ? $contactFormStates : [];
    $contactTurnstileSiteKey = (isset($contactTurnstileSiteKey) && is_string($contactTurnstileSiteKey)) ? trim($contactTurnstileSiteKey) : '';
    $publicSettings = is_array($publicSettings ?? null) ? $publicSettings : [];
    $themeCssHref = ($assetBaseUrl !== '' ? $assetBaseUrl : '') . '/assets/css/theme.css?v=' . (int)$themeVersion;
    $sportVoiceCssHref = ($assetBaseUrl !== '' ? $assetBaseUrl : '') . '/assets/css/sport-voice.css?v=' . (int)$sportVoiceVersion;
    $sportVoiceJsHref = ($assetBaseUrl !== '' ? $assetBaseUrl : '') . '/assets/js/sport-voice.js?v=' . (int)$sportVoiceJsVersion;
    $brandCssHref = ($assetBaseUrl !== '' ? $assetBaseUrl : '') . '/assets/css/brand.php?v=' . (int)$brandVersion;
    $faviconFallbackUrl = ($assetBaseUrl !== '' ? $assetBaseUrl : '') . '/favicon.ico';
    ?>
    <link rel="preload" href="<?= htmlspecialchars(($assetBaseUrl !== '' ? $assetBaseUrl : '') . '/assets/sport-voice/montserrat-800.woff', ENT_QUOTES, 'UTF-8') ?>" as="font" type="font/woff" crossorigin>
    <?php if (!$usesEditorialBlocks || $needsLegacyStyles): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($themeCssHref, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($sportVoiceCssHref, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($brandCssHref, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($faviconUrl !== ''): ?>
    <link rel="icon" href="<?= htmlspecialchars($faviconUrl, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <style>
      :root {
        --site-favicon-url: url('<?= htmlspecialchars($faviconUrl !== '' ? $faviconUrl : $faviconFallbackUrl, ENT_QUOTES, 'UTF-8') ?>');
      }
      /* Safety override: honor per-block hero overlay even with cached/legacy theme.css */
      .block-hero[style*="background-image"]::before {
        background: rgba(0, 0, 0, var(--hero-overlay-opacity, 0)) !important;
      }
    </style>
    <title><?php echo e($title ?? 'Seite'); ?></title>
    <?php
    $seo = is_array($seo ?? null) ? $seo : [];
    $metaDesc   = (string)($seo['description'] ?? $seo['meta_description'] ?? '');
    $robots     = (string)($seo['robots'] ?? '');
    $canonical  = (string)($seo['canonical_url'] ?? '');
    $ogTitle    = (string)($seo['og_title'] ?? $seo['title'] ?? $seo['meta_title'] ?? '');
    $ogDesc     = (string)($seo['og_description'] ?? $metaDesc);
    $ogImage    = (string)($seo['og_image_url'] ?? '');
    ?>
    <?php if ($metaDesc !== ''): ?>
    <meta name="description" content="<?= htmlspecialchars($metaDesc, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <?php if ($robots !== ''): ?>
    <meta name="robots" content="<?= htmlspecialchars($robots, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <?php if ($canonical !== ''): ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <?php if ($ogTitle !== ''): ?>
    <meta property="og:title" content="<?= htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <?php if ($ogDesc !== ''): ?>
    <meta property="og:description" content="<?= htmlspecialchars($ogDesc, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <?php if ($ogImage !== ''): ?>
    <meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <script src="<?= htmlspecialchars($sportVoiceJsHref, ENT_QUOTES, 'UTF-8') ?>" defer></script>
</head>
<body class="sport-voice-page">
    <?php
    $headerNavItems = array_values(array_filter($navItems ?? [], static function (array $item): bool {
        return (string)($item['area'] ?? 'header') !== 'footer';
    }));
    $footerNavItems = array_values(array_filter($navItems ?? [], static function (array $item): bool {
        return (string)($item['area'] ?? '') === 'footer';
    }));
    if (!$previewMainOnly) {
        render('templates/partials/header.php', compact('siteName', 'headerNavItems', 'slug', 'headerLogoUrl', 'faviconUrl', 'assetBaseUrl', 'blocks'));
    }
    ?>
    
    <main id="main">
        <?php render('templates/page.php', compact('pageTitle', 'pageSubtitle', 'blocks', 'contactFormStates', 'slug', 'contactTurnstileSiteKey', 'publicSettings', 'client', 'assetBaseUrl')); ?>
    </main>
    
    <?php if (!$previewMainOnly) { render('templates/partials/footer.php', compact('siteName', 'footerNavItems', 'assetBaseUrl')); } ?>
    <dialog id="photo-dialog" class="photo-dialog" aria-labelledby="photo-caption">
        <button class="dialog-close" type="button" aria-label="Foto schliessen">&times;</button>
        <img id="dialog-image" alt="">
        <p id="photo-caption"></p>
    </dialog>
</body>
</html>

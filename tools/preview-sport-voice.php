<?php
declare(strict_types=1);

// Local router: php -S 127.0.0.1:8095 -t . tools/preview-sport-voice.php
if (PHP_SAPI !== 'cli-server') {
    http_response_code(404);
    exit;
}
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path !== '/') {
    return false;
}
require dirname(__DIR__) . '/app/view.php';
$presets = json_decode(file_get_contents(dirname(__DIR__) . '/pagebuilder/presets.json'), true, 512, JSON_THROW_ON_ERROR);
$blocks = $presets['sport-voice-home']['blocks'];
render('templates/layout.php', [
    'siteName' => 'Sport Voice', 'title' => 'Sport Voice - Sebastian Wauer',
    'pageTitle' => 'Sport Voice', 'pageSubtitle' => '', 'slug' => 'home',
    'blocks' => $blocks, 'navItems' => [], 'headerLogoUrl' => '', 'assetBaseUrl' => '',
]);

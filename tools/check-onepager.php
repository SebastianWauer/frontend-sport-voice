<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') exit(1);
require dirname(__DIR__) . '/app/onepager.php';
$check = static function (bool $condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
};
$entries = [
    ['id'=>2,'slug'=>'leistungen','title'=>'Leistungen','url'=>'/leistungen'],
    ['id'=>1,'slug'=>'start','title'=>'Start','url'=>'/'],
    ['id'=>3,'slug'=>'kontakt','title'=>'Kontakt','url'=>'/kontakt','in_nav'=>false],
    ['id'=>4,'slug'=>'impressum','title'=>'Impressum','url'=>'/impressum'],
    ['id'=>5,'slug'=>'datenschutz','title'=>'Datenschutz','url'=>'/datenschutz'],
    ['id'=>6,'slug'=>'entwurf','title'=>'Entwurf','status'=>'draft'],
    ['id'=>7,'slug'=>'weiterleitung','title'=>'Weiterleitung'],
];
$called = [];
$pages = onepagerPages($entries, static function ($slug) use (&$called, $entries): array {
    $called[] = $slug;
    foreach ($entries as $entry) if ($entry['slug'] === $slug) return $entry + ['blocks'=>[], 'redirect_type'=>$slug === 'weiterleitung' ? 'url' : 'none'];
    throw new RuntimeException('Unbekannte Seite');
}, $entries[0] + ['blocks'=>[]], 'start');
$check(array_column($pages, 'id') === [1,2,3], 'Reihenfolge, unsichtbare Seite oder Ausschluesse falsch.');
$check(!in_array('leistungen', $called, true), 'Aktuelle Seite unnoetig doppelt geladen.');
$check(!in_array('impressum', $called, true) && !in_array('entwurf', $called, true), 'Rechtsseiten/Entwurf geladen.');
$nav = onepagerNavigation([
    ['slug'=>'kontakt','url'=>'/kontakt'], ['slug'=>'datenschutz','url'=>'/datenschutz'],
    ['slug'=>'kontakt','url'=>'https://external.example/kontakt'],
], $pages);
$check($nav[0]['url'] === '/#page-kontakt-3' && $nav[1]['url'] === '/datenschutz' && $nav[2]['url'] === 'https://external.example/kontakt', 'Navigation falsch umgewandelt.');
$ids = ['events'=>true, 'referenz'=>true];
$blocks = onepagerPrepareBlocks([
    ['type'=>'sport_voice_events'],
    ['type'=>'sport_voice_references','data'=>['item_1_anchor'=>'referenz','item_1_link_url'=>'#referenz']],
], 'page-test-8', $ids);
$check($blocks[0]['anchor'] !== 'events', 'Event-ID kollidiert.');
$check($blocks[1]['data']['item_1_link_url'] === '#' . $blocks[1]['data']['item_1_anchor'], 'Referenzlink verliert Ziel.');
$ids = ['anfrage'=>true, str_repeat('a', 64)=>true, 'contact-5'=>true];
$forms = onepagerPrepareBlocks([
    ['type'=>'contact_form','form_id'=>'ANFRAGE'],
    ['type'=>'contact_form','form_id'=>str_repeat('a', 64)],
    ['type'=>'contact_form'],
], 'page-kontakt-3', $ids, 2);
$check($forms[0]['form_id'] !== 'anfrage' && strlen($forms[1]['form_id']) <= 64 && $forms[2]['form_id'] !== 'contact-5', 'Formular-IDs kollidieren nach Normalisierung.');
echo "PASS: Startseite zuerst; CMS-Reihenfolge; unsichtbare Inhaltsseiten; Rechtsseiten/Entwuerfe/Redirects ausgeschlossen; Sprunglinks; kollisionsfreie Event- und Referenzanker.\n";

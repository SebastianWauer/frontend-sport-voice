# Sport Voice mit kundenspezifischen CMS-Bloecken

Sport Voice verwendet neun eigene Blocktypen. Die bisherigen CMS-Standardtypen
bleiben unveraendert; Sport-Voice-Felder werden nicht an andere Kunden verteilt.

| Bereich | Eigener CMS-Typ |
| --- | --- |
| Bild-Hero | `sport_voice_hero` |
| Logo-Leiste | `sport_voice_logo_strip` |
| Leistungen | `sport_voice_service_showcase` |
| Saison-Einsaetze | `sport_voice_columns` |
| Termine | `sport_voice_events` |
| Referenzen | `sport_voice_references` |
| Fotogalerie | `sport_voice_gallery` |
| Portraet | `sport_voice_text` |
| Kontakt | `sport_voice_contact_form` |

Jeder Typ besitzt seine eigene CMS-Klasse unter
`app/PageBuilder/CustomerBlocks/SportVoice/`. Die Frontend-Renderer koennen
weiterhin bestehende Seiten mit Standardtypen darstellen.

## Freigabe und Seitenvorlage

1. In der Verwaltung Migration `030_customer_pagebuilder_blocks.sql` anwenden.
2. Beim Kunden den Frontend-Key `sport-voice` pruefen.
3. Unter **Pagebuilder-Bloecke** die neun Sport-Voice-Typen freigeben.
   Standardtypen koennen zusaetzlich einzeln ausgewaehlt werden.
4. CMS fuer diesen Kunden ausrollen; die Freigaben werden dabei synchronisiert.
5. Im CMS-Seiteneditor **Sport Voice Startseite** auswaehlen,
   **Vorlage einsetzen** und die Seite speichern.

Die Vorlage erscheint nur, wenn alle enthaltenen Typen freigegeben sind.
Sie liegt in `pagebuilder/presets.json` des konfigurierten Frontends.
Vorhandene Event-Einstellungen bleiben beim Einsetzen erhalten;
vor dem Ersetzen bestehender Bloecke wird nachgefragt.

Ohne explizite Freigaben bleiben nur die alten Standardtypen verfuegbar.
Eine leere Auswahl bedeutet keine Bloecke. Falsche Kundenkennungen oder
ungueltige Freigaben geben keine kundenspezifischen Typen frei.
Gesperrte Inhalte werden beim Speichern nicht still entfernt.

## Inhalte

Logo-Leisten haben bis zu acht Logos. Referenzen haben bis zu zwoelf Eintraege
mit frei waehlbaren Kategorien; die Filter entstehen daraus. Die Galerie
verwendet den Medien-Picker mit Alternativtext und zwei Beschriftungen.
Die Saison zeigt bis zu sechs Eintraege. Events verwenden die Eventverwaltung.

Der Kontaktblock unterstuetzt den vorhandenen serverseitigen CMS-Versand
mit seinen Sicherheitspruefungen. Die Seitenvorlage verwendet wie die
HTML-Vorlage `delivery=mailto`: ein Entwurf in der E-Mail-App.
Der Empfaenger und die abgefragten Felder sind einstellbar.

## Onepager

Im oeffentlichen Frontend erscheinen alle veroeffentlichten Inhaltsseiten
untereinander: zuerst die Startseite, dann die CMS-Seitenreihenfolge.
Auch Seiten ohne Navigationseintrag werden als Inhalt aufgenommen.
Impressum und Datenschutz bleiben eigenstaendige Seiten. Entwuerfe,
geloeschte Seiten und Weiterleitungsseiten werden nicht zusammengesetzt.

Die CMS-Navigation springt zu den jeweiligen Seitenabschnitten. Bisherige
Inhaltsseiten-URLs leiten bei GET zum passenden Abschnitt auf der Startseite
weiter. Kontaktformulare senden weiterhin an ihre eigene Ursprungsseite;
deren Signaturen und Formularmeldungen bleiben voneinander getrennt.

Die Umsetzung liegt ausschliesslich im Sport-Voice-Frontend, nicht im CMS.
Eine Seite muss nicht in einen grossen Pagebuilder-Block kopiert werden.
Lokaler Regressionstest: `php tools/check-onepager.php`.

## Vorlagenvorschau

Ohne CMS-Zugang, mit Vorlageninhalten und leerem Events-Block:

```sh
php -S 127.0.0.1:8095 -t . tools/preview-sport-voice.php
```

URL: `http://127.0.0.1:8095/`. Nur fuer den lokalen Entwicklungsserver.

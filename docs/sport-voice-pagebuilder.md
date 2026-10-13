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

## Website-Modus und Onepager

In der Verwaltung unter **Kunde > Website-Darstellung** zwischen
**Mehrseitige Website** und **Onepager** waehlen. Der Modus wird beim
Kunden-CMS-Rollout uebernommen. Blockfreigaben bleiben davon unabhaengig.

Bei aktiviertem Modus unter **CMS > Seiten > Onepager** die sichtbaren Seiten
auswaehlen und per Drag-and-drop oder Pfeiltasten sortieren.
Die Startseite ist fest zuerst. Die erstmalige Vorbelegung setzt Kontakt
nach unten und schliesst Impressum und Datenschutz aus.
Nach dem Speichern gelten ausschliesslich die ausdruecklichen Einstellungen.
Neue Seiten werden nicht automatisch hinzugefuegt; Entwuerfe und
Weiterleitungen werden nicht in den Onepager aufgenommen.

Ausgeschlossene veroeffentlichte Seiten bleiben separat erreichbar.
Die Menueinstellungen bleiben unabhaengig. Seitenwahl und Reihenfolge
wirken sofort im Frontend, ohne erneuten Frontend-Rollout.
Das Frontend liest `website_mode` und `onepager_page_ids` aus der CMS-API.
Bei mehrseitiger Darstellung wird nur die aufgerufene Seite gerendert.

Die Navigation springt fuer ausgewaehlte Seiten zu den Abschnitten; deren
bisherige Seiten-URLs leiten bei GET dorthin weiter. Kontaktformulare
behalten ihre Ursprungsseite und getrennte Formularmeldungen.

Lokaler Regressionstest: `php tools/check-onepager.php`.

## Vorlagenvorschau

Ohne CMS-Zugang, mit Vorlageninhalten und leerem Events-Block:

```sh
php -S 127.0.0.1:8095 -t . tools/preview-sport-voice.php
```

URL: `http://127.0.0.1:8095/`. Nur fuer den lokalen Entwicklungsserver.

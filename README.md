# Sport Voice Frontend

Eigenstaendiges Kundenfrontend fuer Sport Voice. Inhalte, Navigation, Medien und
Einstellungen werden ueber `CMS_API_URL` aus der zugeordneten CMS-Instanz
bezogen. Dieses Repository darf nicht als Standardfrontend anderer Kunden
ausgerollt werden.

## Lokal starten

1. `.env.example` nach `.env` kopieren und `CMS_API_URL` setzen.
2. `php -S localhost:8002 index.php` starten.

`.env`, Cache, Logs, Schluessel und sonstige Laufzeitdaten bleiben lokal.

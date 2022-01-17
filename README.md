# terminplaner
Terminplaner und Termin-Anfragesystem für die Webseite

## Systemvoraussetzungen
- Apache Webserver 2.4.x
- PHP 8.x
- MySQL/MariaDB

## Konfiguration Datenbank
- Datei terminplaner.sql in MySQL einspielen: Datenbank 'terminplaner' wird erstellt mit dazugehöriger Struktur
- Datei include/connect_db.php anpassen zum Zugriff der Webanwendung auf MySQL

## Konfiguration Terminplaner
- Datei include/config.inc.php anpassen (diverse Konfigurationen: Rasterung der Sprechstunden, ...)

## Webseiten
- Kopieren Sie das gesamte Verzeichnis auf den Apache-Webserver in ein Unterverzeichnis 'terminplaner'

## Starten der Webanwendung
http://localhost/terminplaner/index.php

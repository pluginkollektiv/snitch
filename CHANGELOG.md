# Snitch changelog

## 1.2.0
* **English**
  * Shows schema of request (http/https)
  * No "jerking" in the retrieval list during mouse over
  * Remove `lang` folder in favor of translations via translate.wordpress.org (#40)
  * Support for WordPress 6.4
* **Deutsch**
  * Anzeige des Schemas, ob der Aufruf per http oder https erfolgte
  * Kein "ruckeln" in der Abrufliste bei Mouseover
  * `lang` Ordner zugunsten der Übersetzung durch translate.wordpress.org entfernt (#40)
  * Unterstützung für WordPress 6.4

## 1.1.8
* **English**
   * Support for WordPress 5.2
   * Bugfix: Deprecated Non-static call 
* **Deutsch**
   * Unterstützung für WordPress 5.2
   * Fehlerbehebung: Deprecated Non-static call 

## 1.1.7
* **English**
   * Updated README
   * Improved user interface
   * Support for WordPress 4.9
* **Deutsch**
   * Readme aktualisiert
   * Nutzer-Interface verbessert
   * Unterstützung für WordPress 4.9

## 1.1.6
* **English**
   * Updated README
   * Updated [plugin authors](https://pluginkollektiv.org/hello-world/)
* **Deutsch**
   * Readme aktualisiert
   * [Plugin Autor](https://pluginkollektiv.org/hello-world/) aktualisiert

## 1.1.5
* **English**
   * [GitHub Repository](https://github.com/pluginkollektiv/snitch) and [Wiki](https://snitch.pluginkollektiv.org/documentation/)
* **Deutsch**
   * [GitHub Repository](https://github.com/pluginkollektiv/snitch) und [Wiki](https://snitch.pluginkollektiv.org/de/dokumentation/)

## 1.1.4
* **English**
   * Support for WordPress 4.2
   * Nice to have: `admin_url()` for `edit.php` requests
* **Deutsch**
   * Unterstützung für WordPress 4.2
   * Nice to have: `admin_url()` für `edit.php` Aufrufe

## 1.1.3
* **English**
   * Support for WordPress 4.1
* **Deutsch**
   * Support für WordPress 4.1

## 1.1.2
* **English**
   * feature: english translation for the readme file
   * feature: russian translation for plugin files
* **Deutsch**
   * Englische Übersetzung für `readme.txt`
   * Russische Übersetzung für Plugin-Dateien

## 1.1.1
* **English**
   * feature: status code “-1” for failing connections
* **Deutsch**
   * Status-Code „-1“ bei nicht zustande gekommenen Verbindungen

## 1.1.0
* **English**
   * feature: execution time as metric (thanks [Matthias Kilian](https://www.gaertner.de) for the idea)
* **Deutsch**
   * Protokollierung und Anzeige der Ausführungsdauer

## 1.0.12
* **English**
   * extensive consideration of user roles
   * copy adjustments
* **Deutsch**
   * Berücksichtigung von Nutzerrollen bei Plugin-Aktionen
   * Anpassungen textlicher Natur

## 1.0.11
* **English**
   * support for WordPress 3.9
   * source code face lifting
* **Deutsch**
   * Unterstützung für WordPress 3.9
   * Aufräumarbeiten im Sourcecode

## 1.0.10
* **English**
   * change: $pre as return value of function `inspect_request`
* **Deutsch**
   * Änderung des Rückgabewertes in der Funktion `inspect_request`

## 1.0.9
* **English**
   * introduction of constant SNITCH_IGNORE_INTERNAL_REQUESTS
   * optimization for WordPress 3.8
* **Deutsch**
   * Optionale Nicht-Protokollierung interner Verbindungen via PHP-Konstante
   * Optimierung für WordPress 3.8

## 1.0.8
* **English**
   * output POST data on click
   * support for WordPress 3.6
* **Deutsch**
   * Schaltfläche für die Ausgabe der POST-Variablen
   * Kompatibilität zu WordPress 3.6
   * [detailierte Informationen im Blog](https://snitch.pluginkollektiv.org/2013/07/29/snitch-1-0-8-published/)

## 1.0.7
* **English**
   * removal of obsolete "New" link from the toolbar
   * prevention of direct file calls
* **Deutsch**
   * Eliminierung des Links "Neu" in der Admin-Toolbar
   * Unterbindung direkter Aufrufe von Plugin-Dateien

## 1.0.6
* **English**
   * set function `delete_items` to `public`
* **Deutsch**
   * Funktion `delete_items` umbenannt und auf `public` gestellt

## 1.0.5
* **English**
   * storage of a maximum of 200 *Snitch* entries
* **Deutsch**
   * Aufbewahrung von maximal 200 *Snitch*-Einträgen. Ältere werden gelöscht.

## 1.0.4
* **English**
   * new: searching of target URLs
* **Deutsch**
   * Suche in Werten der Spalte *Ziel-URL* (Eingabefeld oben rechts)

## 1.0.3
* **English**
   * new: button *Empty Protocol*
   * removed: avoidance of trash
* **Deutsch**
   * Button *Protokoll leeren* fürs Entfernen aller Einträge
   * Kein Verschieben der Einträge in den Papierkorb (Nutzerwunsch)

## 1.0.2
* **English**
   * renaming of custom field keys to avoid conflict
* **Deutsch**
   * Änderung der Custom Fields Namen für Vermeidung eventueller Konflikte

## 1.0.1
* **English**
   * fix for `Call to undefined function get_current_screen`
* **Deutsch**
   * Korrektur für möglichen Fehler `Call to undefined function get_current_screen`

## 1.0.0
* **English**
   * *Snitch* goes online
* **Deutsch**
   * *Snitch* geht online

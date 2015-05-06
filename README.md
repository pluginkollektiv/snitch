# Snitch

Vertrauen ist gut, Kontrolle ist besser: Verbindungsübersicht zur Überwachung und Steuerung des Datenverkehrs im WordPress-Projekt.

*Snitch* - vom Englischen übersetzt *Petze*, *Spitzel*, *Plaudertasche* - überwacht und protokolliert den ausgehenden Datenstrom in WordPress. Jede Verbindung aus WordPress heraus wird aufgezeichnet und Administratoren in tabellarischer Form zur Verfügung gestellt.

Verbindungsanfragen werden seitens *Snitch* nicht nur mitgeschrieben, auch können zukünftige Versuche blockiert werden: Wahlweise abhängig von der Ziel-URL (Internet-Adresse, die im Hintergrund aufgerufen wird) oder aber vom ausgeführten Skript (Datei, die die Verbindung angefordert hat). Blockierte Verbindungen hebt das WordPress-Plugin visuell hervor. Bereits gesperrte Einträge können per Klick freigegeben werden.

*Snitch* ist perfektes Werkzeug fürs „Mithören“ der Kommunikation nach „Außen“. Auch geeignet für die Früherkennung von in WordPress eingeschmuggelter Malware und Tracking-Software.


> _Snitch_ im offiziellen WordPress-Pluginverzeichnis: [https://wordpress.org/plugins/snitch/](https://wordpress.org/plugins/snitch/)


## Vorteile

* Übersichtliche Oberfläche
* Anzeige der Ziel-URL und Ursprungsdatei
* Gruppierung, Sortierung und Durchsuchen
* Optische Hervorhebung geblockter Anfragen
* POST-Daten per Klick anzeigbar
* Blockieren/Freigabe der Verbindungen nach Domain/Datei
* Überwachung der Kommunikation im Backend und Frontend
* Löschung aller Einträge per Knopfdruck
* Kosten- und werbefrei


## Zusammenfassung

*Snitch* führt ein Logbuch mit allen autorisierten und blockierten Versuchen der Konnektivität. Die Übersicht verschafft Transparenz und Kontrolle über ausgehende Verbindungen, die von Plugins, Themes und WordPress selbst ausgelöst wurden.

___


### Anforderungen

* PHP 5.2.4
* WordPress ab 3.8


### Speicherbelegung

* Backend: ~ 0,32 MB
* Frontend: ~ 0,27 MB


### Sprachen
* Deutsch
* English
* Русский


___


### Autor

* [Sergej Müller](http://sergejmueller.me)


### Unterstützer

* [Caspar Hübinger](http://glueckpress.com)
* [Bego Mario Garde](https://garde-medienberatung.de)


### Links

* [Wiki](https://github.com/sergejmueller/snitch/wiki) *(Dokumentation)*
* [Changelog](https://github.com/sergejmueller/snitch/blob/master/CHANGELOG.md)

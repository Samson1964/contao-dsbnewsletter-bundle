# DSB-Newsletter Changelog

## Version 1.0.0 (2026-08-28)

* Add: Unterstützung für Contao 5.7 neben Contao 4.13; PHP bis einschließlich 8.4.
* Add: Hauptschalter „Inhaltselemente verwenden" am Newsletter (`tl_newsletter.dsbItems`),
  Standard aus. Ist er nicht gesetzt, erscheint der Bearbeitungsknopf nicht und der
  Insert-Tag liefert nichts.
* Add: Beim Einschalten der Inhaltselemente wird der Insert-Tag `{{newsletter::ID}}`
  selbsttätig ans Ende des HTML-Inhalts gesetzt, sofern dort noch keiner steht. Der
  Redakteur muss ihn nicht mehr von Hand tippen; verschieben oder entfernen lässt er
  sich weiterhin, ohne dass er beim nächsten Speichern zurückkehrt.
* Add: Migration, die den neuen Schalter einmalig bei allen Newslettern setzt, die
  bereits Inhaltselemente besitzen — bestehende Newsletter verlieren ihre Abschnitte
  also nicht.
* Add: Ausrichtung des Bildes zum Text (oben, unten, links, rechts). Bei seitlicher
  Ausrichtung wird eine Tabelle mit `align`-Attribut erzeugt, weil Outlook `float`
  nicht auswertet.
* Add: Das Bild kann verlinkt werden. Die Adresse kommt aus dem Feld „Bildlink-Adresse"
  beziehungsweise aus den Metadaten der Datei.
* Add: Backend-Vorschau zeigt das Bild des Abschnitts als Miniaturansicht, in der
  eingestellten Ausrichtung.
* Add: Alternativtext und Bildtitel werden ausgegeben; die Felder waren bisher
  vorhanden, blieben aber wirkungslos.
* Add: README um eine vollständige Anleitung erweitert.
* Change: Das Textfeld eines Abschnitts ist keine Pflichtangabe mehr, damit auch
  reine Bildabschnitte möglich sind.
* Change: Bildadressen werden über `Environment::get('base')` statt
  `Environment::get('url')` gebildet — das trägt auch Installationen in einem
  Unterverzeichnis Rechnung.
* Change: Die Bildunterschrift kommt jetzt in der Sprache der Oberfläche aus den
  Metadaten (bisher fest „de").
* Fix: `Controller::replaceInsertTags()` durch den Dienst `contao.insert_tag.parser`
  ersetzt — die statische Methode gibt es unter Contao 5 nicht mehr.
* Fix: Alle Contao-Klassen mit vollem Namensraum angesprochen. Contao 5 legt keine
  globalen Klassenaliasse mehr an, `\Frontend`, `\Database`, `\Image` und so weiter
  liefen dort ins Leere.
* Fix: `'dataContainer' => 'Table'` durch `DC_Table::class` ersetzt; den Kurznamen
  gibt es unter Contao 5 nicht mehr.
* Fix: Die Dateiendungen der Bildauswahl kommen aus `%contao.image.valid_extensions%`.
  `Config::get('validImageTypes')` existiert unter Contao 5 nicht mehr.
* Fix: Der eigene `toggleIcon`-Rückruf ist entfallen. Contao baut den Umschalter in
  beiden Fassungen selbst, und unter Contao 5 hätte der eigene Rückruf das eingebaute
  Verhalten verdrängt.
* Fix: Das neue Feld wird mit dem `PaletteManipulator` eingehängt, weil sich die
  Standardpalette von `tl_newsletter` zwischen Contao 4.13 und 5 unterscheidet.
* Fix: Fehlende Bilddateien und beschädigte Bilder führen nicht mehr zu einem Fehler,
  sondern werden übergangen.
* Change: Abhängigkeiten `codefog/contao-haste` und `schachbulle/contao-helper-bundle`
  entfernt — sie wurden nirgends benutzt.
* Delete: Das nie benutzte Feld `size` aus `tl_newsletter_items` entfernt. Es stand in
  keiner Palette und war nicht bearbeitbar; beim Datenbank-Update bietet Contao das
  Löschen der Spalte an. Damit entfällt auch der Rückruf auf den Bildgrößen-Dienst.
* Delete: Reste einer anderen Erweiterung entfernt (`mod_dsbnewsletter.html5`,
  `default.css`, die Sprachdateien `default.php` und `modules.php` sowie der
  auskommentierte Backend-Modul-Eintrag).
* Change: Die Dienstdefinition heißt jetzt `services.yaml`; der `_instanceof`-Eintrag
  auf `ContainerAwareInterface` ist entfallen, die Schnittstelle wurde in Symfony 7
  entfernt.

## Version 0.1.3 (2024-12-10)

* Fix: Warning: Trying to access array offset on false in src/Classes/NewsletterLaden.php (line 82) 
* Add: CSS-Klasse und CSS-ID bei den Inhaltselementen hinzugefügt

## Version 0.1.2 (2024-12-10)

* Change: {{env::url}} ersetzt durch \Environment::get('url') in NewsletterLaden
* Fix: Inserttags in den Inhaltselementen werden nicht aufgelöst -> \Controller::replaceInsertTags ergänzt um den Inhalt zu parsen

## Version 0.1.1 (2024-11-14)

* Fix: Keine Bildauswahl in Contao 9 möglich: '%contao.image.valid_extensions%' ersetzt durch \Config::get('validImageTypes')
* Delete: tl_dsbnewsletter

## Version 0.1.0 (2024-11-14)

* Erweiterung ausgebaut
* Change: Inhaltselemente nach unterhalb tl_newsletter verschoben
* Add: Ausbau der Inserttag-Ersetzung mittels {{newsletter::id}}

## Version 0.0.1 (2024-10-29)

* Initialversion als Contao-4-Bundle

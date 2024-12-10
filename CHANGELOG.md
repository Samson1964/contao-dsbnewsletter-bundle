# DSB-Newsletter Changelog

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

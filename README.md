# DSB-Newsletter für Contao

Erweitert den Contao-Newsletter um frei sortierbare **Inhaltselemente**: Jeder
Newsletter lässt sich aus Abschnitten mit Überschrift, Bild und Text
zusammensetzen, statt den gesamten HTML-Inhalt in einem einzigen Textfeld zu
pflegen.

* **Contao:** 4.13 LTS und 5.7
* **PHP:** 7.4 bis 8.4

## Installation

Über den Contao Manager das Paket `schachbulle/contao-dsbnewsletter-bundle`
suchen und installieren, oder auf der Konsole:

```bash
composer require schachbulle/contao-dsbnewsletter-bundle
```

Anschließend die Datenbank aktualisieren (Contao Manager → Systemwartung, oder
`vendor/bin/contao-console contao:migrate`).

## Anleitung

### 1. Inhaltselemente an einem Newsletter einschalten

Die Erweiterung greift nur dort, wo sie ausdrücklich eingeschaltet ist. Im
Newsletter selbst gibt es dafür unterhalb des HTML-Inhalts das Feld
**„Inhaltselemente verwenden"**. Solange es nicht gesetzt ist, bleibt der
Newsletter unverändert und der Bearbeitungsknopf für die Abschnitte erscheint
nicht.

> Bei der Aktualisierung von einer älteren Fassung wird der Schalter bei allen
> Newslettern, die bereits Inhaltselemente besitzen, automatisch gesetzt. Das
> geschieht einmalig beim Datenbank-Update.

### 2. Abschnitte anlegen

In der Newsletter-Übersicht führt der Knopf **„Inhaltselemente bearbeiten"** in
die Liste der Abschnitte. Dort lassen sie sich anlegen, per Ziehen sortieren und
einzeln unsichtbar schalten.

Je Abschnitt stehen zur Verfügung:

| Feld | Bedeutung |
| --- | --- |
| Überschrift | Text und Ebene (`h1` bis `h6`). Bleibt das Feld leer, wird keine Überschrift ausgegeben. |
| Ein Bild verwenden | Schaltet die Bildfelder frei. |
| Bild | Datei aus der Dateiverwaltung. |
| Ausrichtung | `oben`, `unten`, `links` oder `rechts` — bezogen auf den Text. |
| Metadaten überschreiben | Alternativtext, Bildtitel, Bildlink und Bildunterschrift abweichend von der Dateiverwaltung setzen. |
| Bildlink-Adresse | Optional. Ist sie gesetzt, führt ein Klick auf das Bild dorthin. |
| Text | Optional. Der Abschnitt darf auch nur aus Überschrift und Bild bestehen. |
| CSS-ID/Klasse | Wird an das umschließende `div` geschrieben. |
| Unsichtbar | Nimmt den Abschnitt aus dem Newsletter, ohne ihn zu löschen. |

### 3. Abschnitte in den Newsletter einsetzen

Die Abschnitte erscheinen an der Stelle, an der im HTML-Inhalt des Newsletters
der Insert-Tag steht:

```
{{newsletter::ID}}
```

`ID` ist dabei die Datensatz-ID des Newsletters, also die Zahl, die in der
Newsletter-Liste angezeigt wird.

**Von Hand tippen muß man ihn nicht:** Sobald „Inhaltselemente verwenden"
eingeschaltet und gespeichert wird, setzt die Erweiterung den passenden Tag ans
Ende des HTML-Inhalts — vorausgesetzt, dort steht noch keiner. Anschließend läßt
er sich frei verschieben, so daß die Abschnitte über, unter oder zwischen dem
übrigen Inhalt stehen. Wer ihn entfernt, bekommt ihn beim nächsten Speichern
nicht wieder untergeschoben; eingesetzt wird nur beim Einschalten des Feldes.

Der Insert-Tag wirkt sowohl beim Mailversand als auch im Frontend-Modul
„Newsletter-Leser", das den Newsletter im Archiv der Website darstellt.

### 4. Erzeugtes Markup

Jeder Abschnitt wird als eigenes `div` mit der Klasse `abschnitt` ausgegeben:

```html
<div class="abschnitt">
    <h2>Überschrift</h2>
    <div style="margin:0 0 10px 0"><img src="…"><div>Bildunterschrift</div></div>
    <p>Text …</p>
</div>
```

Bei den Ausrichtungen `links` und `rechts` steht das Bild in einer Tabelle mit
`align`-Attribut. Das ist Absicht: Outlook wertet `float` in CSS nicht aus,
`align` an einer Tabelle dagegen schon.

## Wichtiger Hinweis zu Links im Newsletter

Damit Links auf die eigene Website beim Empfänger funktionieren, muß im
Newsletter-Template im `head`-Bereich der `base`-Tag gesetzt sein:

```html
<head>
    …
    <base href="https://deine-domain.de/">
    …
</head>
```

Bildadressen erzeugt die Erweiterung von sich aus absolut, damit sie auch ohne
`base`-Tag im Postfach geladen werden.

## Entwickler

**Frank Hoppe**

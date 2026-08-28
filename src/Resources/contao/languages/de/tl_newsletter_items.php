<?php

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

/**
 * Eingabeformular
 */
$GLOBALS['TL_LANG']['tl_newsletter_items']['title_legend'] = 'Überschrift';
$GLOBALS['TL_LANG']['tl_newsletter_items']['headline'] = array('Überschrift', 'Überschrift des Abschnitts. Bleibt das Feld leer, wird keine Überschrift ausgegeben.');

$GLOBALS['TL_LANG']['tl_newsletter_items']['text_legend'] = 'Text';
$GLOBALS['TL_LANG']['tl_newsletter_items']['text'] = array('Text', 'Sie können HTML-Tags verwenden, um den Text zu formatieren. Das Feld darf leer bleiben, wenn der Abschnitt nur aus einem Bild bestehen soll.');

$GLOBALS['TL_LANG']['tl_newsletter_items']['image_legend'] = 'Bildeinstellungen';
$GLOBALS['TL_LANG']['tl_newsletter_items']['useImage'] = array('Ein Bild verwenden', 'Bitte verwenden Sie nur Bilder in Originalauflösung, die für den Newsletter aufbereitet wurden. Die Bilder werden unverändert übernommen.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['singleSRC'] = array('Bild', 'Bitte wählen Sie ein Bild aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['floating'] = array('Ausrichtung', 'Legt fest, ob das Bild über, unter, links oder rechts vom Text steht. Bei „links" und „rechts" umfließt der Text das Bild.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['overwriteMeta'] = array('Metadaten überschreiben', 'Die Metadaten aus der Dateiverwaltung überschreiben.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['alt'] = array('Alternativer Text', 'Hier können Sie einen alternativen Text für das Bild eingeben (&lt;em&gt;alt&lt;/em&gt;-Attribut).');
$GLOBALS['TL_LANG']['tl_newsletter_items']['imageTitle'] = array('Bildtitel', 'Hier können Sie den Titel des Bildes eingeben (&lt;em&gt;title&lt;/em&gt;-Attribut).');
$GLOBALS['TL_LANG']['tl_newsletter_items']['imageUrl'] = array('Bildlink-Adresse', 'Wird eine Adresse angegeben, führt ein Klick auf das Bild dorthin. Ohne Angabe bleibt das Bild unverlinkt.');
$GLOBALS['TL_LANG']['tl_newsletter_items']['caption'] = array('Bildunterschrift', 'Hier können Sie einen kurzen Text eingeben, der unterhalb des Bildes angezeigt wird.');

$GLOBALS['TL_LANG']['tl_newsletter_items']['expert_legend'] = 'Experteneinstellungen';
$GLOBALS['TL_LANG']['tl_newsletter_items']['cssID'] = array('CSS-ID/Klasse', 'Hier können Sie eine ID und beliebig viele Klassen eingeben.');

$GLOBALS['TL_LANG']['tl_newsletter_items']['invisible_legend'] = 'Sichtbarkeit';
$GLOBALS['TL_LANG']['tl_newsletter_items']['invisible'] = array('Unsichtbar', 'Den Abschnitt nicht in den Newsletter übernehmen.');

/**
 * Weitere Beschriftungen
 */
$GLOBALS['TL_LANG']['tl_newsletter_items']['noHeadline'] = '- ohne Überschrift -';

/**
 * Buttons für Operationen
 */
$GLOBALS['TL_LANG']['tl_newsletter_items']['new'] = array('Neuer Abschnitt', 'Neuen Abschnitt anlegen');
$GLOBALS['TL_LANG']['tl_newsletter_items']['edit'] = array('Abschnitt bearbeiten', 'Abschnitt ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_newsletter_items']['copy'] = array('Abschnitt duplizieren', 'Abschnitt ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_newsletter_items']['cut'] = array('Abschnitt verschieben', 'Abschnitt ID %s verschieben');
$GLOBALS['TL_LANG']['tl_newsletter_items']['delete'] = array('Abschnitt löschen', 'Abschnitt ID %s löschen');
$GLOBALS['TL_LANG']['tl_newsletter_items']['toggle'] = array('Abschnitt anzeigen/verbergen', 'Abschnitt ID %s anzeigen/verbergen');
$GLOBALS['TL_LANG']['tl_newsletter_items']['show'] = array('Abschnittsdetails anzeigen', 'Details des Abschnitts ID %s anzeigen');

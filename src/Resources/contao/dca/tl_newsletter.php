<?php

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

use Contao\Controller;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\Image;
use Contao\StringUtil;
use Schachbulle\ContaoDsbnewsletterBundle\Classes\NewsletterCallbacks;

/**
 * Die Inhaltselemente hängen als Kindtabelle unter dem Newsletter. Der Eintrag
 * wird angehängt statt gesetzt, damit eine bereits von anderer Seite
 * eingetragene Kindtabelle erhalten bleibt.
 */
$GLOBALS['TL_DCA']['tl_newsletter']['config']['ctable'][] = 'tl_newsletter_items';

/**
 * Setzt den Insert-Tag selbsttätig in den HTML-Inhalt, sobald die
 * Inhaltselemente eingeschaltet werden. Der Rückruf muss hier stehen und nicht
 * am Feld: Contao schreibt den Datensatz erst nach allen `save_callback`s, ein
 * dort abgesetztes UPDATE auf `content` würde wieder überschrieben.
 */
$GLOBALS['TL_DCA']['tl_newsletter']['config']['onsubmit_callback'][] = array(NewsletterCallbacks::class, 'addInsertTag');

/**
 * Hauptschalter: Ohne ihn bleibt der Newsletter unverändert, der
 * Bearbeitungsknopf erscheint nicht und der Insert-Tag liefert nichts.
 * Der Schalter steht direkt hinter dem HTML-Inhalt, weil er dessen Ausgabe
 * ergänzt.
 */
$GLOBALS['TL_DCA']['tl_newsletter']['fields']['dsbItems'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_newsletter']['dsbItems'],
	'exclude'               => true,
	'filter'                => true,
	'inputType'             => 'checkbox',
	'eval'                  => array('tl_class'=>'w50 clr'),
	'save_callback'         => array
	(
		array(NewsletterCallbacks::class, 'rememberActivation')
	),
	'sql'                   => "char(1) COLLATE ascii_bin NOT NULL default ''"
);

// PaletteManipulator statt fester Zeichenkette, weil sich die Standardpalette
// zwischen Contao 4.13 und Contao 5 unterscheidet (dort steht zusätzlich
// "preheader" im HTML-Bereich).
PaletteManipulator::create()
	->addField('dsbItems', 'content', PaletteManipulator::POSITION_AFTER)
	->applyToPalette('default', 'tl_newsletter');

/**
 * Bearbeitungsknopf für die Inhaltselemente.
 */
$GLOBALS['TL_DCA']['tl_newsletter']['list']['operations']['editContent'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_newsletter']['editContent'],
	'href'                  => 'table=tl_newsletter_items',
	'icon'                  => 'bundles/contaodsbnewsletter/images/editContent.png',

	/**
	 * Blendet den Knopf aus, solange der Newsletter die Inhaltselemente nicht nutzt.
	 *
	 * Der Rückruf wird als Closure hinterlegt, weil eine Klasse namens
	 * `tl_newsletter` bereits vom Newsletter-Bundle stammt und nicht ein
	 * zweites Mal deklariert werden darf.
	 *
	 * Er benutzt bewusst die alte Signatur mit Einzelwerten: Contao 5 reicht
	 * seit 5.5 ein `DataContainerOperation`-Objekt herein, fällt aber für
	 * Rückrufe mit mehr als einem Parameter auf genau diese Aufrufweise
	 * zurück. Eine leere Zeichenkette blendet den Knopf in beiden Fassungen
	 * aus; `null` täte das nur unter Contao 4.13, weshalb im aktiven Fall das
	 * Markup selbst erzeugt wird.
	 *
	 * @param array        $row        Datensatz des Newsletters
	 * @param string|null  $href       Ziel aus der Operationsdefinition, ohne die Datensatz-ID
	 * @param string       $label      Beschriftung für das Symbol
	 * @param string       $title      Titel des Verweises, bereits mit der Newsletter-Bezeichnung gefüllt
	 * @param string|null  $icon       Pfad zum Symbol
	 * @param string|object $attributes Zusätzliche Attribute; unter Contao 5 ein Objekt,
	 *                                  das sich in eine Zeichenkette umwandeln lässt
	 *
	 * @return string Der fertige Verweis, oder eine leere Zeichenkette, wenn die
	 *                Inhaltselemente an diesem Newsletter abgeschaltet sind
	 */
	'button_callback'       => static function ($row, $href, $label, $title, $icon, $attributes)
	{
		if (empty($row['dsbItems']))
		{
			return '';
		}

		$strHref = Controller::addToUrl($href.'&amp;id='.$row['id']);

		return '<a href="'.$strHref.'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}
);

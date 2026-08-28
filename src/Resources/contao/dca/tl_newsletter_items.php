<?php

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

use Contao\Config;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\FilesModel;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;

/**
 * Tabelle tl_newsletter_items
 *
 * Die Inhaltselemente eines Newsletters. Sie werden vom Insert-Tag
 * {{newsletter::<ID>}} ausgelesen und in den Newsletter eingesetzt.
 */
$GLOBALS['TL_DCA']['tl_newsletter_items'] = array
(

	// Config
	'config' => array
	(
		// Unter Contao 5 gibt es den Kurznamen "Table" nicht mehr, der
		// vollständige Klassenname funktioniert in beiden Fassungen.
		'dataContainer'             => DC_Table::class,
		'ptable'                    => 'tl_newsletter',
		'enableVersioning'          => true,
		'sql' => array
		(
			'keys' => array
			(
				'id'                            => 'primary',
				'pid'                           => 'index',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => DataContainer::MODE_PARENT,
			'fields'                  => array('sorting'),
			'flag'                    => 1,
			'headerFields'            => array('subject'),
			'panelLayout'             => 'sort,filter;search,limit',
			'child_record_callback'   => array('tl_newsletter_items', 'listContent'),
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_items']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_items']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_items']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.svg'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_items']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\'))return false;Backend.getScrollOffset()"'
			),
			// Kein eigener button_callback: Contao 4.13 wie Contao 5 bauen den
			// Ajax-Umschalter selbst, sobald am Feld 'toggle' gesetzt ist.
			// 'reverse' dreht das Symbol um, weil das Feld die Unsichtbarkeit
			// speichert und nicht die Sichtbarkeit.
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_items']['toggle'],
				'href'                => 'act=toggle&amp;field=invisible',
				'icon'                => 'visible.svg',
				'reverse'             => true
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_newsletter_items']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('useImage', 'overwriteMeta'),
		'default'                     => '{title_legend},headline;{image_legend},useImage;{text_legend},text;{expert_legend:hide},cssID;{invisible_legend:hide},invisible'
	),

	// Subpalettes
	'subpalettes' => array
	(
		// Keine Bildgröße: im Newsletter werden nur bereits aufbereitete
		// Originalbilder verwendet, weil viele Mailprogramme mit skalierten
		// Bildern schlecht umgehen.
		'useImage'                    => 'singleSRC,floating,overwriteMeta',
		'overwriteMeta'               => 'alt,imageTitle,imageUrl,caption'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'headline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['headline'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
			'eval'                    => array('maxlength'=>200, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NOT NULL default 'a:2:{s:5:\"value\";s:0:\"\";s:4:\"unit\";s:2:\"h2\";}'"
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['text'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			// Kein 'mandatory': ein Abschnitt darf auch nur aus Überschrift
			// und Bild bestehen.
			'eval'                    => array('rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NULL"
		),
		'useImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['useImage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) COLLATE ascii_bin NOT NULL default ''"
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array
			(
				'filesOnly'           => true,
				'fieldType'           => 'radio',
				// Der Platzhalter wird von Widget::getAttributesFromDca() über
				// den Parameter-Behälter aufgelöst — in Contao 4.13 wie in
				// Contao 5. Die frühere Einstellung Config::get('validImageTypes')
				// gibt es unter Contao 5 nicht mehr.
				'extensions'          => '%contao.image.valid_extensions%',
				'mandatory'           => true,
				'tl_class'            => 'clr'
			),
			'sql'                     => "binary(16) NULL"
		),
		'floating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['floating'],
			'exclude'                 => true,
			'inputType'               => 'radioTable',
			'options'                 => array('above', 'left', 'right', 'below'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('cols'=>4, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) COLLATE ascii_bin NOT NULL default 'above'"
		),
		'overwriteMeta' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['overwriteMeta'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'w50 clr'),
			'sql'                     => "char(1) COLLATE ascii_bin NOT NULL default ''"
		),
		'alt' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['alt'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'imageTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['imageTitle'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'imageUrl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['imageUrl'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'dcaPicker'=>true, 'tl_class'=>'w50'),
			'sql'                     => "text NULL"
		),
		'caption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['caption'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'allowHtml'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'invisible' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_items']['invisible'],
			'exclude'                 => true,
			'toggle'                  => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) COLLATE ascii_bin NOT NULL default ''"
		),
	)
);

/**
 * Hilfsmethoden für die Datenkonfiguration.
 *
 * Die Klasse erbt bewusst von keiner Contao-Klasse: Sie braucht weder den
 * Datenbank-Import noch die Hilfsmethoden von `Contao\Backend`, und der
 * Konstruktor von `Contao\Controller` würde unter Contao 5 nur unnötige
 * Veraltungsmeldungen erzeugen. `System::importStatic()` erzeugt auch
 * Klassen ohne Elternklasse.
 */
class tl_newsletter_items
{
	/**
	 * Baut die Vorschauzeile eines Inhaltselements in der Backend-Liste.
	 *
	 * Angezeigt werden Überschrift, ein verkleinertes Vorschaubild — sofern am
	 * Element eines hinterlegt ist — und der Textanfang. Die Höhe wird von
	 * Contao begrenzt, sofern der Benutzer die Einstellung „Elemente nicht
	 * einklappen" nicht gesetzt hat.
	 *
	 * @param array<string,mixed> $arrRow Der Datensatz aus `tl_newsletter_items`
	 *
	 * @return string Das HTML der Vorschauzeile
	 */
	public function listContent($arrRow)
	{
		$strKey = $arrRow['invisible'] ? 'unpublished' : 'published';

		$arrHeadline = StringUtil::deserialize($arrRow['headline'] ?? null, true);
		$strTitle = trim((string) ($arrHeadline['value'] ?? ''));

		if ('' === $strTitle)
		{
			$strTitle = $GLOBALS['TL_LANG']['tl_newsletter_items']['noHeadline'] ?? '- ohne Überschrift -';
		}

		$strClass = 'limit_height';

		// Höhe begrenzen, wenn der Benutzer das Einklappen nicht abgeschaltet hat
		if (!Config::get('doNotCollapse'))
		{
			$strClass .= ' h40';
		}

		$strPreview = '';

		if ($arrRow['useImage'])
		{
			$strPreview = $this->getPreviewImage((string) ($arrRow['floating'] ?? 'above'), $arrRow['singleSRC'] ?? null);
		}

		return '
<div class="cte_type ' . $strKey . '">' . $strTitle . '</div>
<div class="' . trim($strClass) . '">
' . $strPreview . StringUtil::insertTagToSrc((string) ($arrRow['text'] ?? '')) . '
</div>' . "\n";
	}

	/**
	 * Erzeugt ein kleines Vorschaubild für die Backend-Liste.
	 *
	 * Das Bild wird über `contao.image.factory` auf Vorschaugröße gebracht,
	 * damit die Liste nicht die vollen Newsletter-Bilder laden muss. Die
	 * Ausrichtung des Elements wird nachgebildet, damit im Backend zu erkennen
	 * ist, wie der Abschnitt später aussieht.
	 *
	 * @param string      $strFloating Ausrichtung: `above`, `below`, `left` oder `right`
	 * @param string|null $varUuid     Die binäre UUID der Datei aus `singleSRC`
	 *
	 * @return string Das `img`-Element in einem ausgerichteten `div`, oder eine
	 *                leere Zeichenkette, wenn die Datei fehlt oder sich nicht
	 *                verkleinern lässt (etwa bei einem defekten Bild)
	 */
	private function getPreviewImage($strFloating, $varUuid)
	{
		$objFile = FilesModel::findByUuid($varUuid);

		if (null === $objFile)
		{
			return '';
		}

		$container = System::getContainer();
		$strProjectDir = $container->getParameter('kernel.project_dir');

		try
		{
			$objImage = $container->get('contao.image.factory')->create(
				$strProjectDir . '/' . $objFile->path,
				array(100, 75, 'box')
			);

			$strSrc = $objImage->getUrl($strProjectDir);
		}
		catch (Throwable $e)
		{
			return ''; // Datei ist kein verwertbares Bild
		}

		$strStyle = 'margin:0 10px 5px 0';

		if ('right' === $strFloating)
		{
			$strStyle = 'float:right;margin:0 0 5px 10px';
		}
		elseif ('left' === $strFloating)
		{
			$strStyle = 'float:left;margin:0 10px 5px 0';
		}

		return '<div style="' . $strStyle . '">' . Image::getHtml($strSrc, '', 'style="max-width:100px;height:auto"') . '</div>';
	}
}

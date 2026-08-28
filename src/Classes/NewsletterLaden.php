<?php

declare(strict_types=1);

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

namespace Schachbulle\ContaoDsbnewsletterBundle\Classes;

use Contao\Database;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Frontend;
use Contao\StringUtil;
use Contao\System;

/**
 * Setzt die Inhaltselemente eines Newsletters in HTML um.
 *
 * Die Klasse hängt am Hook `replaceInsertTags` und beantwortet den Insert-Tag
 * `{{newsletter::<ID>}}`. Sie erbt bewusst von keiner Contao-Klasse: Benötigt
 * werden ausschließlich statische Dienste, und der Konstruktor von
 * `Contao\Frontend` würde unter Contao 5 eine Veraltungsmeldung auslösen.
 *
 * Der Hook `replaceInsertTags` gilt seit Contao 5.2 als veraltet; der Ersatz
 * (das Attribut `#[AsInsertTag]`) existiert unter Contao 4.13 jedoch nicht.
 * Solange beide Fassungen unterstützt werden, ist der Hook der einzige
 * gemeinsame Weg.
 */
class NewsletterLaden
{
	/**
	 * Beantwortet den Insert-Tag `{{newsletter::<ID>}}`.
	 *
	 * Contao ruft die Methode für jeden nicht erkannten Insert-Tag auf. Alles,
	 * was nicht mit `newsletter` beginnt, muss deshalb mit `false` abgelehnt
	 * werden, damit andere Erweiterungen den Tag noch bekommen.
	 *
	 * @param string $strTag Der Insert-Tag ohne die geschweiften Klammern,
	 *                       zum Beispiel `newsletter::12`. Contao stellt bei
	 *                       zwischenspeicherbaren Tags `cache_` voran.
	 *
	 * @return string|false Der fertige HTML-Block, ein Hinweistext bei fehlender
	 *                      ID, oder `false`, wenn der Tag nicht zu dieser
	 *                      Erweiterung gehört
	 */
	public function run(string $strTag)
	{
		$arrSplit = explode('::', $strTag);

		if ('newsletter' !== $arrSplit[0] && 'cache_newsletter' !== $arrSplit[0])
		{
			return false; // Tag gehört nicht zu dieser Erweiterung
		}

		if (!isset($arrSplit[1]) || '' === trim($arrSplit[1]))
		{
			return 'Newsletter-ID fehlt!';
		}

		return $this->getContent((int) $arrSplit[1]);
	}

	/**
	 * Baut den HTML-Block aus allen sichtbaren Inhaltselementen eines Newsletters.
	 *
	 * Die Elemente stehen in `tl_newsletter_items` und werden nach `sorting`
	 * ausgegeben. Ist am Newsletter der Schalter `dsbItems` nicht gesetzt, wird
	 * bewusst nichts geliefert — so bleibt ein stehen gebliebener Insert-Tag in
	 * einem umgestellten Newsletter wirkungslos, statt alte Elemente wieder
	 * hervorzuholen.
	 *
	 * Insert-Tags innerhalb der Texte werden hier aufgelöst. Beim Mailversand
	 * geschieht das rechtzeitig genug, dass Contao anschließend noch die
	 * relativen Adressen in absolute umschreibt (siehe `Newsletter::send()`).
	 *
	 * @param int $intNewsletter Datensatz-ID aus `tl_newsletter`
	 *
	 * @return string Der HTML-Block, oder eine leere Zeichenkette, wenn der
	 *                Newsletter unbekannt ist, die Funktion dort abgeschaltet
	 *                ist oder kein sichtbares Element existiert
	 */
	public function getContent(int $intNewsletter): string
	{
		$objDatabase = Database::getInstance();

		// Hauptschalter am Newsletter prüfen
		$objNewsletter = $objDatabase
			->prepare('SELECT dsbItems FROM tl_newsletter WHERE id=?')
			->limit(1)
			->execute($intNewsletter);

		if ($objNewsletter->numRows < 1 || !$objNewsletter->dsbItems)
		{
			return '';
		}

		$objItems = $objDatabase
			->prepare('SELECT * FROM tl_newsletter_items WHERE pid=? AND invisible=? ORDER BY sorting ASC')
			->execute($intNewsletter, '');

		if ($objItems->numRows < 1)
		{
			return '';
		}

		$strContent = '';

		while ($objItems->next())
		{
			$strContent .= $this->parseItem($objItems->row());
		}

		return $strContent;
	}

	/**
	 * Setzt ein einzelnes Inhaltselement in HTML um.
	 *
	 * Die Reihenfolge von Bild und Text richtet sich nach dem Feld `floating`:
	 * bei `above`/`below` steht das Bild als eigener Block über beziehungsweise
	 * unter dem Text, bei `left`/`right` läuft der Text daneben.
	 *
	 * @param array<string,mixed> $arrItem Der vollständige Datensatz aus `tl_newsletter_items`
	 *
	 * @return string Der HTML-Abschnitt einschließlich umschließendem `div`
	 */
	private function parseItem(array $arrItem): string
	{
		// CSS-ID und CSS-Klassen stehen serialisiert in einem Feld
		$arrCss = StringUtil::deserialize($arrItem['cssID'] ?? null, true);
		$strCssId = trim((string) ($arrCss[0] ?? ''));
		$strCssClass = trim((string) ($arrCss[1] ?? ''));

		$strAttributes = '' !== $strCssId ? ' id="'.StringUtil::specialchars($strCssId).'"' : '';
		$strAttributes .= ' class="abschnitt'.('' !== $strCssClass ? ' '.StringUtil::specialchars($strCssClass) : '').'"';

		$strItem = '<div'.$strAttributes.'>';

		// Überschrift steht als array('unit' => 'h2', 'value' => '…') in der Spalte
		$arrHeadline = StringUtil::deserialize($arrItem['headline'] ?? null, true);
		$strHeadline = trim((string) ($arrHeadline['value'] ?? ''));

		if ('' !== $strHeadline)
		{
			$strUnit = $arrHeadline['unit'] ?? 'h2';
			$strItem .= '<'.$strUnit.'>'.$strHeadline.'</'.$strUnit.'>';
		}

		$strFloating = $arrItem['floating'] ?? 'above';
		$strImage = $arrItem['useImage'] ? $this->parseImage($arrItem, $strFloating) : '';

		// Der Text ist nicht mehr Pflicht, damit auch reine Bildabschnitte möglich sind
		$strText = '';

		if ('' !== trim((string) ($arrItem['text'] ?? '')))
		{
			$strText = System::getContainer()->get('contao.insert_tag.parser')->replaceInline((string) $arrItem['text']);
		}

		if ('below' === $strFloating)
		{
			$strItem .= $strText.$strImage;
		}
		else
		{
			$strItem .= $strImage.$strText;
		}

		// Bei seitlich stehendem Bild muss der Umfluss am Ende des Abschnitts
		// aufgehoben werden, sonst rutscht der nächste Abschnitt daneben
		if ('' !== $strImage && ('left' === $strFloating || 'right' === $strFloating))
		{
			$strItem .= '<div style="clear:both;font-size:0;line-height:0">&nbsp;</div>';
		}

		return $strItem.'</div>';
	}

	/**
	 * Erzeugt das Bild eines Inhaltselements samt Bildunterschrift und Verlinkung.
	 *
	 * Die Adresse wird absolut aufgebaut, weil das Bild im Postfach des
	 * Empfängers geladen wird und dort kein `base`-Tag der Website greift.
	 * Für seitlich stehende Bilder wird eine Tabelle mit `align`-Attribut
	 * verwendet: Outlook wertet `float` in CSS nicht aus, `align` an einer
	 * Tabelle dagegen schon.
	 *
	 * @param array<string,mixed> $arrItem     Der Datensatz aus `tl_newsletter_items`
	 * @param string              $strFloating Ausrichtung: `above`, `below`, `left` oder `right`
	 *
	 * @return string Das fertige HTML, oder eine leere Zeichenkette, wenn die
	 *                hinterlegte Datei nicht mehr existiert
	 */
	private function parseImage(array $arrItem, string $strFloating): string
	{
		$objFile = FilesModel::findByUuid($arrItem['singleSRC'] ?? null);

		if (null === $objFile)
		{
			return ''; // Datei wurde zwischenzeitlich gelöscht
		}

		$arrMeta = Frontend::getMetaData($objFile->meta, $GLOBALS['TL_LANGUAGE'] ?? 'de');

		// Metadaten aus der Dateiverwaltung lassen sich am Element überschreiben
		if ($arrItem['overwriteMeta'])
		{
			$strAlt = (string) ($arrItem['alt'] ?? '');
			$strTitle = (string) ($arrItem['imageTitle'] ?? '');
			$strCaption = (string) ($arrItem['caption'] ?? '');
			$strUrl = (string) ($arrItem['imageUrl'] ?? '');
		}
		else
		{
			$strAlt = (string) ($arrMeta['alt'] ?? '');
			$strTitle = (string) ($arrMeta['title'] ?? '');
			$strCaption = (string) ($arrMeta['caption'] ?? '');
			$strUrl = (string) ($arrMeta['link'] ?? '');
		}

		$strSrc = Environment::get('base').$objFile->path;

		$strImg = '<img src="'.StringUtil::specialchars($strSrc).'" alt="'.StringUtil::specialchars($strAlt).'"';

		if ('' !== $strTitle)
		{
			$strImg .= ' title="'.StringUtil::specialchars($strTitle).'"';
		}

		$strImg .= ' style="border:0;max-width:100%;height:auto">';

		// Bildlink ist optional
		if ('' !== $strUrl)
		{
			$strUrl = System::getContainer()->get('contao.insert_tag.parser')->replaceInline($strUrl);
			$strImg = '<a href="'.StringUtil::specialcharsUrl($strUrl).'">'.$strImg.'</a>';
		}

		if ('' !== $strCaption)
		{
			$strCaption = '<div style="font-size:12px;line-height:16px;padding-top:4px">'.$strCaption.'</div>';
		}

		if ('left' === $strFloating || 'right' === $strFloating)
		{
			$strMargin = 'left' === $strFloating ? '0 15px 10px 0' : '0 0 10px 15px';

			return '<table align="'.$strFloating.'" border="0" cellpadding="0" cellspacing="0" style="float:'.$strFloating.';margin:'.$strMargin.'"><tr><td>'.$strImg.$strCaption.'</td></tr></table>';
		}

		$strMargin = 'below' === $strFloating ? '10px 0 0 0' : '0 0 10px 0';

		return '<div style="margin:'.$strMargin.'">'.$strImg.$strCaption.'</div>';
	}
}

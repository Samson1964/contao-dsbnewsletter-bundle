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

/**
 * Rückrufe der Datenkonfiguration von `tl_newsletter`.
 *
 * Die Klasse setzt den Insert-Tag `{{newsletter::<ID>}}` selbsttätig in den
 * HTML-Inhalt, sobald am Newsletter die Inhaltselemente eingeschaltet werden.
 * Der Redakteur muss ihn dadurch nicht mehr von Hand tippen, ohne dass der
 * Einsetzmechanismus verlassen wird: Contao löst den Tag beim Versand früh
 * genug auf, dass die relativen Adressen in den Abschnitten anschließend noch
 * in absolute umgeschrieben werden.
 *
 * Die Klasse erbt bewusst von keiner Contao-Klasse; `System::importStatic()`
 * erzeugt auch Klassen ohne Elternklasse.
 */
class NewsletterCallbacks
{
	/**
	 * Merkt sich je Newsletter-ID, dass der Schalter in diesem Aufruf von „aus"
	 * auf „ein" gewechselt ist.
	 *
	 * Die Eigenschaft ist statisch, weil Contao die beiden Rückrufe nicht
	 * zwingend über dieselbe Objektinstanz aufruft: Der `save_callback` läuft
	 * unter Contao 4.13 über `Controller::import()`, der `onsubmit_callback`
	 * unter Contao 5 über `System::importStatic()`.
	 *
	 * @var array<int,bool>
	 */
	private static $arrActivated = array();

	/**
	 * Erkennt das Einschalten der Inhaltselemente. Registriert als `save_callback`
	 * am Feld `dsbItems`.
	 *
	 * Zum Zeitpunkt des Aufrufs steht in der Datenbank noch der alte Wert: Contao
	 * sammelt die Feldwerte und schreibt sie erst nach allen `save_callback`s in
	 * einem einzigen UPDATE. Dadurch lässt sich der Wechsel erkennen, ohne auf
	 * `$dc->activeRecord` zurückzugreifen, das ab Contao 5 als veraltet gilt.
	 *
	 * @param mixed  $varValue Der neue Wert des Feldes, '1' oder ''
	 * @param object $dc       Der Data Container; es wird nur `$dc->id` benötigt,
	 *                         deshalb ohne Typhinweis, damit die Signatur unter
	 *                         Contao 4.13 wie unter Contao 5 passt
	 *
	 * @return mixed Der unveränderte Wert; der Rückruf greift nicht in die
	 *               Speicherung ein, sondern merkt sich nur den Wechsel
	 */
	public function rememberActivation($varValue, $dc)
	{
		if (!$varValue)
		{
			return $varValue;
		}

		$intId = (int) $dc->id;

		if ($intId < 1)
		{
			return $varValue;
		}

		$objOld = Database::getInstance()
			->prepare('SELECT dsbItems FROM tl_newsletter WHERE id=?')
			->limit(1)
			->execute($intId);

		if ($objOld->numRows && !$objOld->dsbItems)
		{
			self::$arrActivated[$intId] = true;
		}

		return $varValue;
	}

	/**
	 * Hängt den Insert-Tag an den HTML-Inhalt an. Registriert als `onsubmit_callback`
	 * an `tl_newsletter`.
	 *
	 * Der Rückruf läuft erst, nachdem Contao den Datensatz geschrieben hat — ein
	 * eigenes UPDATE im `save_callback` würde vom gesammelten UPDATE wieder
	 * überschrieben. Die neue Fassung entsteht danach, der eingesetzte Tag steht
	 * also auch in der Versionsgeschichte.
	 *
	 * Es wird ausschließlich beim Wechsel von „aus" auf „ein" eingegriffen und nur,
	 * wenn noch kein Newsletter-Tag im Inhalt steht. Wer den Tag später bewusst
	 * herausnimmt oder verschiebt, bekommt ihn nicht beim nächsten Speichern
	 * wieder untergeschoben.
	 *
	 * @param object $dc Der Data Container; benötigt wird `$dc->id`
	 *
	 * @return void
	 */
	public function addInsertTag($dc): void
	{
		$intId = (int) $dc->id;

		if (empty(self::$arrActivated[$intId]))
		{
			return;
		}

		unset(self::$arrActivated[$intId]);

		$objDatabase = Database::getInstance();

		$objNewsletter = $objDatabase
			->prepare('SELECT content FROM tl_newsletter WHERE id=?')
			->limit(1)
			->execute($intId);

		if ($objNewsletter->numRows < 1)
		{
			return;
		}

		$strContent = self::appendTag((string) $objNewsletter->content, $intId);

		if (null === $strContent)
		{
			return;
		}

		$objDatabase
			->prepare('UPDATE tl_newsletter SET content=? WHERE id=?')
			->execute($strContent, $intId);
	}

	/**
	 * Hängt den Insert-Tag an einen HTML-Inhalt an.
	 *
	 * Die Methode ist von der Datenbank getrennt, damit sie sich ohne
	 * Contao-Umgebung prüfen lässt.
	 *
	 * @param string $strContent Der bisherige HTML-Inhalt des Newsletters
	 * @param int    $intId      Die Datensatz-ID des Newsletters
	 *
	 * @return string|null Der neue Inhalt, oder `null`, wenn bereits ein
	 *                     Newsletter-Tag enthalten ist und nichts zu tun bleibt
	 */
	public static function appendTag(string $strContent, int $intId): ?string
	{
		// Ein bereits vorhandener Tag — gleich für welchen Newsletter und ob
		// zwischengespeichert — wird nicht angetastet
		if (preg_match('/\{\{(cache_)?newsletter::/', $strContent))
		{
			return null;
		}

		$strTag = '{{newsletter::' . $intId . '}}';

		// Der Tag steht bewusst auf einer eigenen Zeile und nicht in einem
		// Absatz: Die Abschnitte sind eigene div-Blöcke und dürfen nicht in
		// einem p-Element landen.
		return '' !== trim($strContent) ? rtrim($strContent) . "\n" . $strTag : $strTag;
	}
}

<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2024 Leo Feyer
 *
 * @package   DSB-Newsletter
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2024
 */

namespace Schachbulle\ContaoDsbnewsletterBundle\Classes;

class NewsletterLaden extends \Frontend
{

	public function run($strTag)
	{
		$arrSplit = explode('::', $strTag);

		// Inserttag {{newsletter::id}}
		// Liefert zu Newsletter-Id die Inhaltselemente
		if($arrSplit[0] == 'newsletter' || $arrSplit[0] == 'cache_newsletter')
		{
			// Parameter angegeben?
			if(isset($arrSplit[1]))
			{
				return self::getContent($arrSplit[1]);
			}
			else
			{
				return 'Newsletter-ID fehlt!';
			}
		}
		else
		{
			return false; // Tag nicht dabei
		}

	}

	public function getContent($newsletter_id)
	{
		// Inhaltselemente des Newsletters laden
		$objContent = \Database::getInstance()->prepare("SELECT * FROM tl_newsletter_items WHERE pid=? ORDER BY sorting ASC")
		                                      ->execute($newsletter_id);

		$content = '';
		// Inhaltselemnte parsen
		if($objContent->numRows)
		{
			while($objContent->next())
			{
				// Abschnitt hinzufügen, wenn nicht ausgeblendet
				if(!$objContent->invisible)
				{
					// Neuen Abschnitt beginnen
					$content .= '<div class="abschnitt">';
					// Überschrift extrahieren
					$headline = unserialize($objContent->headline); // Bsp.: array('unit' => 'h2', 'value' => 'Überschrift')
					if($headline['value'])
					{
						$content .= '<'.$headline['unit'].'>'.$headline['value'].'</'.$headline['unit'].'>';
					}
					// Bild extrahieren
					if($objContent->useImage)
					{
						// Suche nach UUID
						$objFile = \FilesModel::findByUuid($objContent->singleSRC); 
						// arrData holen
						$arrFile = $objFile->row(); //Array (arrData:protected)
						// Bildunterschrift bauen
						if($objContent->overwriteMeta)
						{
							$bildunterschrift = $objContent->caption;
						}
						else
						{
							$meta = unserialize($arrFile['meta']);
							$bildunterschrift = $meta['de']['caption'];
						}
						$content .= '<figure><img src="'.\Environment::get('url').'/'.$arrFile['path'].'"><figcaption>'.$bildunterschrift.'</figcaption></figure>';
					}
					// Text extrahieren
					$content .= \Controller::replaceInsertTags($objContent->text);
					// Abschnitt beenden
					$content .= '</div>';
				}
			}
		}

		return $content;
	}
}

<?php

declare(strict_types=1);

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

namespace Schachbulle\ContaoDsbnewsletterBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\NewsletterBundle\ContaoNewsletterBundle;
use Schachbulle\ContaoDsbnewsletterBundle\ContaoDsbnewsletterBundle;

/**
 * Meldet das Bundle beim Contao Manager an.
 */
class Plugin implements BundlePluginInterface
{
	/**
	 * Gibt die Bundle-Konfiguration an den Contao Manager zurück.
	 *
	 * Das Bundle wird bewusst nach dem Newsletter-Bundle geladen, weil es
	 * dessen DCA `tl_newsletter` um eine Kindtabelle und ein zusätzliches Feld
	 * erweitert. Würde es vorher geladen, überschriebe der Kern die eigenen
	 * Ergänzungen wieder.
	 *
	 * @param ParserInterface $parser Von Contao gestellter Parser für Konfigurationsdateien,
	 *                                hier nicht benötigt
	 *
	 * @return array<BundleConfig> Liste mit der Konfiguration dieses einen Bundles
	 */
	public function getBundles(ParserInterface $parser): array
	{
		return array(
			BundleConfig::create(ContaoDsbnewsletterBundle::class)
				->setLoadAfter(array(ContaoCoreBundle::class, ContaoNewsletterBundle::class)),
		);
	}
}

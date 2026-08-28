<?php

declare(strict_types=1);

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

namespace Schachbulle\ContaoDsbnewsletterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Lädt die Dienstdefinitionen der Erweiterung in den Symfony-Container.
 */
class ContaoDsbnewsletterExtension extends Extension
{
	/**
	 * Liest `Resources/config/services.yaml` ein.
	 *
	 * Die Basisklasse `Symfony\Component\DependencyInjection\Extension\Extension`
	 * gibt es sowohl in Symfony 5.4 (Contao 4.13) als auch in Symfony 7
	 * (Contao 5.7). `ExtensionInterface::load()` hat in beiden Fassungen keinen
	 * Rückgabetyp, `: void` ist hier deshalb zulässig.
	 *
	 * @param array<mixed>    $mergedConfig Zusammengeführte Konfiguration aus der Anwendung,
	 *                                      wird von dieser Erweiterung nicht ausgewertet
	 * @param ContainerBuilder $container   Container, in den die Dienste eingetragen werden
	 *
	 * @return void
	 */
	public function load(array $mergedConfig, ContainerBuilder $container): void
	{
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../Resources/config')
		);

		$loader->load('services.yaml');
	}
}

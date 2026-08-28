<?php

declare(strict_types=1);

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

namespace Schachbulle\ContaoDsbnewsletterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Symfony-Bundle-Klasse der Erweiterung.
 *
 * Die Klasse bleibt absichtlich leer: Sie meldet das Bundle lediglich beim
 * Kernel an, damit dessen Verzeichnis `src/Resources/contao/` von Contao
 * eingelesen wird. Sowohl Contao 4.13 als auch Contao 5 suchen dort nach
 * DCA-, Sprach- und Konfigurationsdateien.
 */
class ContaoDsbnewsletterBundle extends Bundle
{
}

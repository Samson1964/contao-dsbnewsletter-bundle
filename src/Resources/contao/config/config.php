<?php

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

use Schachbulle\ContaoDsbnewsletterBundle\Classes\NewsletterLaden;

/**
 * Die Inhaltselemente hängen als Kindtabelle unter tl_newsletter und werden
 * deshalb im vorhandenen Backend-Modul „Newsletter" mitverwaltet. Ein eigenes
 * Backend-Modul gibt es bewusst nicht.
 */
$GLOBALS['BE_MOD']['content']['newsletter']['tables'][] = 'tl_newsletter_items';

/**
 * Insert-Tag {{newsletter::<ID>}}
 *
 * Der Hook ist unter Contao 5.2+ als veraltet gekennzeichnet, funktioniert dort
 * aber weiterhin. Das moderne Attribut #[AsInsertTag] gibt es unter Contao 4.13
 * noch nicht, weshalb der Hook der einzige für beide Fassungen gangbare Weg ist.
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array(NewsletterLaden::class, 'run');

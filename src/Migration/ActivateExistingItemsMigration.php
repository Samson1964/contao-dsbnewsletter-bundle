<?php

declare(strict_types=1);

/**
 * DSB-Newsletter für Contao
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 * @copyright Frank Hoppe
 */

namespace Schachbulle\ContaoDsbnewsletterBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

/**
 * Schaltet den neuen Hauptschalter an allen Newslettern ein, die bereits
 * Inhaltselemente besitzen.
 *
 * Ab Fassung 1.0.0 werden die Inhaltselemente nur noch ausgegeben, wenn am
 * Newsletter das Feld `dsbItems` gesetzt ist; der Standard ist „aus". Ohne
 * diese Migration verlören bestehende Newsletter — auch die bereits im
 * Archiv der Website angezeigten — ihre Abschnitte stillschweigend.
 */
class ActivateExistingItemsMigration extends AbstractMigration
{
	/**
	 * @var Connection
	 */
	private $connection;

	/**
	 * @param Connection $connection Datenbankverbindung, von Symfony eingesetzt
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Entscheidet, ob die Migration ausgeführt werden muss.
	 *
	 * Fehlt die Spalte `dsbItems` noch, wird `false` geliefert: Contao führt die
	 * Migrationen ein zweites Mal aus, nachdem das Datenbankschema aktualisiert
	 * wurde, und dann greift die Prüfung.
	 *
	 * Sobald irgendein Newsletter den Schalter gesetzt hat, läuft die Migration
	 * nicht mehr an. So kann ein Redakteur den Schalter später bewusst wieder
	 * ausschalten, ohne dass die Migration ihn beim nächsten Aktualisieren
	 * erneut setzt.
	 *
	 * @return bool True, wenn mindestens ein Newsletter mit Inhaltselementen
	 *              noch keinen gesetzten Schalter hat
	 */
	public function shouldRun(): bool
	{
		$schemaManager = $this->connection->createSchemaManager();

		if (!$schemaManager->tablesExist(array('tl_newsletter', 'tl_newsletter_items')))
		{
			return false;
		}

		$columns = $schemaManager->listTableColumns('tl_newsletter');

		if (!isset($columns['dsbitems']))
		{
			return false; // Schema noch nicht aktualisiert
		}

		// Der Schalter wurde bereits irgendwo benutzt: nicht mehr eingreifen
		if ($this->connection->fetchOne("SELECT id FROM tl_newsletter WHERE dsbItems='1' LIMIT 1"))
		{
			return false;
		}

		return (bool) $this->connection->fetchOne(
			"SELECT n.id FROM tl_newsletter n WHERE EXISTS (SELECT 1 FROM tl_newsletter_items i WHERE i.pid = n.id) LIMIT 1"
		);
	}

	/**
	 * Setzt den Schalter an allen Newslettern mit Inhaltselementen.
	 *
	 * @return MigrationResult Ergebnis mit der Anzahl der geänderten Newsletter
	 */
	public function run(): MigrationResult
	{
		$intCount = $this->connection->executeStatement(
			"UPDATE tl_newsletter n SET n.dsbItems='1' WHERE EXISTS (SELECT 1 FROM tl_newsletter_items i WHERE i.pid = n.id)"
		);

		return $this->createResult(
			true,
			sprintf('Inhaltselemente an %s bestehenden Newslettern eingeschaltet.', $intCount)
		);
	}
}

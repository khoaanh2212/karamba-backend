<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160830083332 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE VIEW appliancesfordealers AS select ca.id, cl.name as clientName, cl.email as clientEmail, cl.position as clientPosition, ca.brand, ca.model, ca.fuel_types, ca.transmissions, ca.number_of_doors, ca.extras, ca.number_of_offers, ca.vehicle_id, a.dealer_id from carappliances as ca
  RIGHT JOIN applianceOffers a on a.appliance_id = ca.id
  RIGHT JOIN clients as cl on cl.id = ca.client_id;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP VIEW appliancesfordealers');
    }
}

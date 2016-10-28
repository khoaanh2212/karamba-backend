<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160927140025 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER VIEW appliancesfordealers AS select ca.id, a.state, a.created, cl.name as clientName, cl.email as clientEmail, cl.city as city, cl.position as clientPosition, ca.brand, ca.model, ca.derivative, ca.transmission, ca.motor_type, ca.number_of_doors, ca.price, ca.package, ca.extras, ca.color, ca.number_of_offers, ca.vehicle_id, a.dealer_id from carappliances as ca
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
        $this->addSql('ALTER VIEW appliancesfordealers AS select ca.id, a.state, cl.name as clientName, cl.email as clientEmail, cl.city as city, cl.position as clientPosition, ca.brand, ca.model, ca.derivative, ca.transmission, ca.motor_type, ca.number_of_doors, ca.price, ca.package, ca.extras, ca.color, ca.number_of_offers, ca.vehicle_id, a.dealer_id from carappliances as ca
  RIGHT JOIN applianceOffers a on a.appliance_id = ca.id
  RIGHT JOIN clients as cl on cl.id = ca.client_id;');
    }
}

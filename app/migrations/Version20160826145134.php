<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160826145134 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE carappliances ADD COLUMN vehicle_id INTEGER NOT NULL');
        $this->addSql('ALTER TABLE carappliances CHANGE fuel_type fuel_types VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE carappliances CHANGE transmission transmissions VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE carappliances CHANGE pack extras VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE carappliances DROP COLUMN vehicle_id');
        $this->addSql('ALTER TABLE carappliances CHANGE fuel_types fuel_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE carappliances CHANGE transmissions transmission VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE carappliances CHANGE extras pack VARCHAR(255) NOT NULL');
    }
}

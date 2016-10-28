<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160808142250 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE stockcars (id VARCHAR(36) NOT NULL, dealer_id VARCHAR(36) NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, year VARCHAR(255) NOT NULL, vehicle_id VARCHAR(255) NOT NULL, fuel_type VARCHAR(255) NOT NULL, derivative VARCHAR(255) NOT NULL, transmission VARCHAR(255) NOT NULL, doors VARCHAR(255) NOT NULL, color LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', extras LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stockcars ADD FOREIGN KEY (dealer_id) REFERENCES dealers(id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE stockcars DROP FOREIGN KEY dealer_id');
        $this->addSql('DROP TABLE stockcars');
    }
}

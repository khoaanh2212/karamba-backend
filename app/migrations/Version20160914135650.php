<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160914135650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE carappliances ADD price DOUBLE PRECISION NOT NULL, ADD package LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', DROP fuel_types, DROP transmissions, DROP number_of_doors, CHANGE extras extras LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', CHANGE color color LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE carappliances ADD fuel_types VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD transmissions VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD number_of_doors VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP price, DROP package, CHANGE extras extras VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE color color VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}

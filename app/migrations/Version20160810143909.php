<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160810143909 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stockcars ADD make_key VARCHAR(255) NOT NULL, ADD model_key VARCHAR(255) NOT NULL, ADD price VARCHAR(255) NOT NULL, ADD price_to_display VARCHAR(255) NOT NULL, CHANGE color color LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', CHANGE extras extras LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', CHANGE pvp pvp VARCHAR(20) NOT NULL, CHANGE cash cash VARCHAR(20) NOT NULL, CHANGE discount discount VARCHAR(20) NOT NULL, CHANGE photo_url photo_url VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stockcars DROP make_key, DROP model_key, DROP price, DROP price_to_display, CHANGE color color LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', CHANGE extras extras LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', CHANGE pvp pvp VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE cash cash VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE discount discount VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE photo_url photo_url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}

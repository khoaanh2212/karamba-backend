<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161018103336 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE `gifts` (`id` INT AUTO_INCREMENT, `gift_value` varchar(16) NOT NULL, `gift_name` varchar(36) NOT NULL, PRIMARY KEY (`id`)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO gifts(gift_value, gift_name) VALUES(\'20 €\', \'Deliberry\')');
        $this->addSql('INSERT INTO gifts(gift_value, gift_name) VALUES(\'10 €\', \'Hailo\')');
        $this->addSql('INSERT INTO gifts(gift_value, gift_name) VALUES(\'10 €\', \'Glovo\')');
        $this->addSql('INSERT INTO gifts(gift_value, gift_name) VALUES(\'10 €\', \'MyTaxi\')');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE gifts IF EXISTS');
    }
}

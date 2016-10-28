<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161020032906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE reviews 
                        (
                          id varchar(36) NOT NULL, dealer_id varchar(36) NOT NULL,
                          client_id varchar(36) NOT NULL,
                          gift_id int(11) NOT NULL,
                          comment text,
                          reviewer_full_name varchar(255) DEFAULT NULL,
                          reviewer_business_name varchar(255) DEFAULT NULL,
                          state varchar(36) DEFAULT NULL,
                          created DATETIME NOT NULL
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('CREATE TABLE reviewdetails 
                        (
                          id varchar(36) NOT NULL,
                          type varchar(100) DEFAULT NULL,
                          rating float DEFAULT NULL
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('DROP TABLE reviewdetails');
    }
}

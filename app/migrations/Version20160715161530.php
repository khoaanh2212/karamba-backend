<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160715161530 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE dealers (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, vendor_name VARCHAR(255) NOT NULL, vendor_role VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, schedule VARCHAR(255) NOT NULL, delivery_conditions VARCHAR(255) NOT NULL, special_conditions VARCHAR(255) NOT NULL, phone_number VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dealer_conditions_assoc CHANGE dealer_id dealer_id VARCHAR(36) NOT NULL, CHANGE condition_id group_id INT NOT NULL, ADD PRIMARY KEY (dealer_id, group_id)');
        $this->addSql('ALTER TABLE dealer_conditions_assoc ADD CONSTRAINT FK_EBF616B7249E6EA1 FOREIGN KEY (dealer_id) REFERENCES dealers (id)');
        $this->addSql('ALTER TABLE dealer_conditions_assoc ADD CONSTRAINT FK_EBF616B7FE54D947 FOREIGN KEY (group_id) REFERENCES dealerconditions (id)');
        $this->addSql('CREATE INDEX IDX_EBF616B7249E6EA1 ON dealer_conditions_assoc (dealer_id)');
        $this->addSql('CREATE INDEX IDX_EBF616B7FE54D947 ON dealer_conditions_assoc (group_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE dealer_conditions_assoc DROP FOREIGN KEY FK_EBF616B7249E6EA1');
        $this->addSql('DROP TABLE dealers');
        $this->addSql('DROP INDEX IDX_EBF616B7249E6EA1 ON dealer_conditions_assoc');
        $this->addSql('DROP INDEX IDX_EBF616B7FE54D947 ON dealer_conditions_assoc');
        $this->addSql('ALTER TABLE dealer_conditions_assoc DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE dealer_conditions_assoc CHANGE dealer_id dealer_id VARCHAR(36) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE group_id condition_id INT NOT NULL');
    }
}

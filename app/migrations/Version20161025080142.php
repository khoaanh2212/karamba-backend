<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161025080142 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('INSERT INTO dealerconditions(condition_name) VALUES(\'FREE_DELIVERY_300\')');
        $this->addSql('INSERT INTO dealerconditions(condition_name) VALUES(\'FREE_DELIVERY_AT_DEALERSHIP\')');
        $this->addSql('INSERT INTO dealerconditions(condition_name) VALUES(\'FREE_TRAVEL_TO_DEALER\')');
        $this->addSql('INSERT INTO dealerconditions(condition_name) VALUES(\'AGENCY_EXPENSES_INCLUDED\')');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DELETE FROM dealerconditions WHERE condition_name = \'FREE_DELIVERY_300\'');
        $this->addSql('DELETE FROM dealerconditions WHERE condition_name = \'FREE_DELIVERY_AT_DEALERSHIP\'');
        $this->addSql('DELETE FROM dealerconditions WHERE condition_name = \'FREE_TRAVEL_TO_DEALER\'');
        $this->addSql('DELETE FROM dealerconditions WHERE condition_name = \'AGENCY_EXPENSES_INCLUDED\'');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160715141247 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('INSERT INTO dealerconditions(condition_name) VALUES(\'FREE_DELIVERY_100\')');
        $this->addSql('INSERT INTO dealerconditions(condition_name) VALUES(\'FULL_GAS\')');
        $this->addSql('INSERT INTO dealerconditions(condition_name) VALUES(\'FREE_TRANSPORT\')');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('TRUNCATE TABLE dealerconditions');
    }
}

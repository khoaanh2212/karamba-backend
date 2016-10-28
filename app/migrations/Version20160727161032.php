<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160727161032 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE dealers MODIFY address VARCHAR(255)');
        $this->addSql('ALTER TABLE dealers MODIFY schedule VARCHAR(255)');
        $this->addSql('ALTER TABLE dealers MODIFY delivery_conditions VARCHAR(255)');
        $this->addSql('ALTER TABLE dealers MODIFY special_conditions VARCHAR(255)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE dealers MODIFY address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE dealers MODIFY schedule VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE dealers MODIFY delivery_conditions VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE dealers MODIFY special_conditions VARCHAR(255) NOT NULL');
    }
}

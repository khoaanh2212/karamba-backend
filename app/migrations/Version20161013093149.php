<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161013093149 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE OR REPLACE VIEW karambacars.appliancesforclients AS
                      SELECT
                        `cl`.`id`          AS `client_id`,
                        `dl`.`id`          AS `dealer_id`,
                        `dl`.`name`        AS `dealer_name`,
                        `dl`.`address`     AS `dealer_address`,
                        `ava`.`image_name` AS `dealer_avatar`,
                        `om`.`created`     AS `message_created`,
                        `om`.`author_type` AS `message_author_type`,
                        `om`.`offer_id`    AS `offer_id`,
                        `om`.`message`     AS `message`,
                        `ca`.`model`       AS `model`
                    
                      FROM ((((`karambacars`.`clients` `cl` INNER JOIN `karambacars`.`carappliances` `ca` ON `cl`.`id` = `ca`.`client_id`)
                        INNER JOIN `karambacars`.`applianceOffers` `ao` ON `ca`.`id` = `ao`.`appliance_id`)
                        LEFT JOIN `karambacars`.`offermessages` `om` ON (`om`.`offer_id` = `ao`.`id`))
                        LEFT JOIN `karambacars`.`dealers` `dl` ON (`ao`.`dealer_id` = `dl`.`id`))
                        LEFT JOIN `karambacars`.`avatars` `ava` ON (`dl`.`id` = `ava`.`dealer_id`)
                ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP VIEW appliancesforclients');
    }
}

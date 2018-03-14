<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180313174840 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer CHANGE answered_at answered_at DATE NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE birthday birthday DATE NOT NULL, CHANGE phone_nr phone_nr VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(255) NOT NULL, CHANGE salary salary DOUBLE PRECISION NOT NULL, CHANGE fuel_consumption fuel_consumption DOUBLE PRECISION NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer CHANGE answered_at answered_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE birthday birthday DATE DEFAULT NULL, CHANGE phone_nr phone_nr VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE role role VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE salary salary DOUBLE PRECISION DEFAULT NULL, CHANGE fuel_consumption fuel_consumption DOUBLE PRECISION DEFAULT NULL');
    }
}

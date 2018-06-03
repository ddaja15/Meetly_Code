<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180603143650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8A76ED395');
        $this->addSql('ALTER TABLE job CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE notification CHANGE created_at created_at DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8A76ED395');
        $this->addSql('ALTER TABLE job CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP');
    }
}

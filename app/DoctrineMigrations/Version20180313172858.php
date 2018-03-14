<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180313172858 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, job_id INT NOT NULL, response LONGTEXT NOT NULL, answered_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_DADD4A25BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE job ADD is_answered TINYINT(1) NOT NULL, DROP answer, DROP finished_at, CHANGE description description VARCHAR(255) NOT NULL, CHANGE deadline deadline DATE NOT NULL, CHANGE priority priority INT NOT NULL, CHANGE reward reward DOUBLE PRECISION NOT NULL, CHANGE job_diff job_diff INT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE answer');
        $this->addSql('ALTER TABLE job ADD answer LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD finished_at DATETIME DEFAULT NULL, DROP is_answered, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE deadline deadline DATE DEFAULT NULL, CHANGE priority priority INT DEFAULT 0 NOT NULL, CHANGE reward reward DOUBLE PRECISION DEFAULT \'0\' NOT NULL, CHANGE job_diff job_diff INT DEFAULT 0 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP');
    }
}

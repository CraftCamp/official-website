<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180618112831 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project__details DROP FOREIGN KEY FK_3B20612C166D1F9C;');
        $this->addSql('ALTER TABLE project__details DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE project__details CHANGE project_id project_id INT DEFAULT NULL, ADD id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_3B20612C166D1F9C ON project__details (project_id)');
        $this->addSql('UPDATE project__details SET id = project_id');
        $this->addSql('ALTER TABLE project__details CHANGE id id INT AUTO_INCREMENT PRIMARY KEY');
        $this->addSql('ALTER TABLE project__details ADD CONSTRAINT FK_3B20612C166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id)');
        $this->addSql('CREATE TABLE project__polls (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, details_id INT DEFAULT NULL, created_at DATETIME NOT NULL, ended_at DATETIME NOT NULL, INDEX IDX_80A0C138166D1F9C (project_id), UNIQUE INDEX UNIQ_80A0C138BB1A0722 (details_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project__votes (id INT AUTO_INCREMENT NOT NULL, poll_id INT DEFAULT NULL, user_id INT DEFAULT NULL, `option` VARCHAR(255) NOT NULL, is_positive TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_CC177D193C947C0F (poll_id), INDEX IDX_CC177D19A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project__polls ADD CONSTRAINT FK_80A0C138166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id)');
        $this->addSql('ALTER TABLE project__polls ADD CONSTRAINT FK_80A0C138BB1A0722 FOREIGN KEY (details_id) REFERENCES project__details (id)');
        $this->addSql('ALTER TABLE project__votes ADD CONSTRAINT FK_CC177D193C947C0F FOREIGN KEY (poll_id) REFERENCES project__polls (id)');
        $this->addSql('ALTER TABLE project__votes ADD CONSTRAINT FK_CC177D19A76ED395 FOREIGN KEY (user_id) REFERENCES users__user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project__details MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE project__details DROP FOREIGN KEY FK_3B20612C166D1F9C;');
        $this->addSql('DROP INDEX IDX_3B20612C166D1F9C ON project__details');
        $this->addSql('ALTER TABLE project__details DROP FOREIGN KEY FK_80A0C138BB1A0722;');
        $this->addSql('ALTER TABLE project__details DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE project__details DROP id, CHANGE project_id project_id INT NOT NULL');
        $this->addSql('ALTER TABLE project__details ADD PRIMARY KEY (project_id)');
        $this->addSql('ALTER TABLE project__votes DROP FOREIGN KEY FK_CC177D193C947C0F');
        $this->addSql('DROP TABLE project__polls');
        $this->addSql('DROP TABLE project__votes');
    }
}

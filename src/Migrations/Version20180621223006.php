<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180621223006 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `project__projects` DROP FOREIGN KEY `FK_636FB313A8D4C349`;');
        $this->addSql('ALTER TABLE `project__projects` ADD CONSTRAINT `FK_636FB313A8D4C349` FOREIGN KEY (`approval_poll_id`) REFERENCES `project__polls`(`id`) ON DELETE SET NULL ON UPDATE RESTRICT;');
        $this->addSql('ALTER TABLE project__details DROP FOREIGN KEY FK_3B20612C166D1F9C;');
        $this->addSql('ALTER TABLE project__details ADD CONSTRAINT FK_3B20612C166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id) ON DELETE CASCADE;');
        $this->addSql('ALTER TABLE project__polls DROP FOREIGN KEY FK_80A0C138166D1F9C;');
        $this->addSql('ALTER TABLE project__polls ADD CONSTRAINT FK_80A0C138166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id) ON DELETE CASCADE;');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `project__projects` DROP FOREIGN KEY `FK_636FB313A8D4C349`;');
        $this->addSql('ALTER TABLE `project__projects` ADD CONSTRAINT `FK_636FB313A8D4C349` FOREIGN KEY (`approval_poll_id`) REFERENCES `project__polls`(`id`);');
        $this->addSql('ALTER TABLE project__details DROP FOREIGN KEY FK_3B20612C166D1F9C;');
        $this->addSql('ALTER TABLE project__details ADD CONSTRAINT FK_3B20612C166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id);');
        $this->addSql('ALTER TABLE project__polls DROP FOREIGN KEY FK_80A0C138166D1F9C;');
        $this->addSql('ALTER TABLE project__polls ADD CONSTRAINT FK_80A0C138166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id);');
    }
}

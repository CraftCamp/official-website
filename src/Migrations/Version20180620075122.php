<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180620075122 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project__projects ADD approval_poll_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project__projects ADD CONSTRAINT FK_636FB313A8D4C349 FOREIGN KEY (approval_poll_id) REFERENCES project__polls (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_636FB313A8D4C349 ON project__projects (approval_poll_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project__projects DROP FOREIGN KEY FK_636FB313A8D4C349');
        $this->addSql('DROP INDEX UNIQ_636FB313A8D4C349 ON project__projects');
        $this->addSql('ALTER TABLE project__projects DROP approval_poll_id');
    }
}

<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180617222251 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project__projects DROP FOREIGN KEY FK_636FB313B58C0B6E');
        $this->addSql('ALTER TABLE project__projects ADD CONSTRAINT FK_636FB313B58C0B6E FOREIGN KEY (product_owner_id) REFERENCES users__user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project__projects DROP FOREIGN KEY FK_636FB313B58C0B6E');
        $this->addSql('ALTER TABLE project__projects ADD CONSTRAINT FK_636FB313B58C0B6E FOREIGN KEY (product_owner_id) REFERENCES users__product_owner (id)');
    }
}

<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180308222215 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users__organizations (user_id INT NOT NULL, organization_id INT NOT NULL, INDEX IDX_9394C5E4A76ED395 (user_id), INDEX IDX_9394C5E432C8A3DE (organization_id), PRIMARY KEY(user_id, organization_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users__organizations ADD CONSTRAINT FK_9394C5E4A76ED395 FOREIGN KEY (user_id) REFERENCES users__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users__organizations ADD CONSTRAINT FK_9394C5E432C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users__user DROP FOREIGN KEY FK_37C1021B32C8A3DE');
        $this->addSql('DROP INDEX IDX_37C1021B32C8A3DE ON users__user');
        $this->addSql('ALTER TABLE users__user DROP organization_id');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE users__organizations');
        $this->addSql('ALTER TABLE users__user ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users__user ADD CONSTRAINT FK_37C1021B32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_37C1021B32C8A3DE ON users__user (organization_id)');
    }
}

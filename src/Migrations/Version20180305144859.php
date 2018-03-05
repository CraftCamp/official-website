<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180305144859 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE developtech_agility__beta_test_users (beta_test_id INT NOT NULL, beta_tester_model_id INT NOT NULL, INDEX IDX_BE77917425B284CA (beta_test_id), INDEX IDX_BE779174986077B (beta_tester_model_id), PRIMARY KEY(beta_test_id, beta_tester_model_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developtech_agility__beta_test_users ADD CONSTRAINT FK_BE77917425B284CA FOREIGN KEY (beta_test_id) REFERENCES developtech_agility__beta_tests (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developtech_agility__beta_test_users ADD CONSTRAINT FK_BE779174986077B FOREIGN KEY (beta_tester_model_id) REFERENCES developtech_agility__beta_testers (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE beta_test_beta_tester');
        $this->addSql('ALTER TABLE developtech_agility__projects ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE developtech_agility__projects ADD CONSTRAINT FK_B71CBD7B32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_B71CBD7B32C8A3DE ON developtech_agility__projects (organization_id)');
        $this->addSql('ALTER TABLE users__user ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users__user ADD CONSTRAINT FK_37C1021B32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_37C1021B32C8A3DE ON users__user (organization_id)');
        $this->addSql('ALTER TABLE users__product_owner DROP FOREIGN KEY FK_BFD444BB32C8A3DE');
        $this->addSql('DROP INDEX IDX_BFD444BB32C8A3DE ON users__product_owner');
        $this->addSql('ALTER TABLE users__product_owner DROP organization_id');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE beta_test_beta_tester (beta_test_id INT NOT NULL, beta_tester_id INT NOT NULL, INDEX IDX_19F86C6A25B284CA (beta_test_id), INDEX IDX_19F86C6A204E40D7 (beta_tester_id), PRIMARY KEY(beta_test_id, beta_tester_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE beta_test_beta_tester ADD CONSTRAINT FK_19F86C6A204E40D7 FOREIGN KEY (beta_tester_id) REFERENCES developtech_agility__beta_testers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE beta_test_beta_tester ADD CONSTRAINT FK_19F86C6A25B284CA FOREIGN KEY (beta_test_id) REFERENCES developtech_agility__beta_tests (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE developtech_agility__beta_test_users');
        $this->addSql('ALTER TABLE developtech_agility__projects DROP FOREIGN KEY FK_B71CBD7B32C8A3DE');
        $this->addSql('DROP INDEX IDX_B71CBD7B32C8A3DE ON developtech_agility__projects');
        $this->addSql('ALTER TABLE developtech_agility__projects DROP organization_id');
        $this->addSql('ALTER TABLE users__product_owner ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users__product_owner ADD CONSTRAINT FK_BFD444BB32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_BFD444BB32C8A3DE ON users__product_owner (organization_id)');
        $this->addSql('ALTER TABLE users__user DROP FOREIGN KEY FK_37C1021B32C8A3DE');
        $this->addSql('DROP INDEX IDX_37C1021B32C8A3DE ON users__user');
        $this->addSql('ALTER TABLE users__user DROP organization_id');
    }
}

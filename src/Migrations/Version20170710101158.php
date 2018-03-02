<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170710101158 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE developtech_agility__repositories__repository (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, type VARCHAR(10) NOT NULL, INDEX IDX_351FAE0B166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developtech_agility__repositories__github (id INT NOT NULL, owner VARCHAR(255) NOT NULL, owner_type VARCHAR(15) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developtech_agility__beta_testers (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D9C191609B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developtech_agility__beta_tests (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, started_at DATETIME NOT NULL, ended_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_ED350ED2166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beta_test_beta_tester (beta_test_id INT NOT NULL, beta_tester_id INT NOT NULL, INDEX IDX_19F86C6A25B284CA (beta_test_id), INDEX IDX_19F86C6A204E40D7 (beta_tester_id), PRIMARY KEY(beta_test_id, beta_tester_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developtech_agility__repositories__repository ADD CONSTRAINT FK_351FAE0B166D1F9C FOREIGN KEY (project_id) REFERENCES developtech_agility__projects (id)');
        $this->addSql('ALTER TABLE developtech_agility__repositories__github ADD CONSTRAINT FK_7E6C0ED3BF396750 FOREIGN KEY (id) REFERENCES developtech_agility__repositories__repository (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developtech_agility__beta_testers ADD CONSTRAINT FK_D9C191609B6B5FBA FOREIGN KEY (account_id) REFERENCES users__user (id)');
        $this->addSql('ALTER TABLE developtech_agility__beta_tests ADD CONSTRAINT FK_ED350ED2166D1F9C FOREIGN KEY (project_id) REFERENCES developtech_agility__projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE beta_test_beta_tester ADD CONSTRAINT FK_19F86C6A25B284CA FOREIGN KEY (beta_test_id) REFERENCES developtech_agility__beta_tests (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE beta_test_beta_tester ADD CONSTRAINT FK_19F86C6A204E40D7 FOREIGN KEY (beta_tester_id) REFERENCES developtech_agility__beta_testers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developtech_agility__projects DROP nbBetaTesters, DROP betaTestStatus');
        $this->addSql('ALTER TABLE developtech_agility__jobs DROP FOREIGN KEY FK_1D411D77166D1F9C');
        $this->addSql('ALTER TABLE developtech_agility__jobs ADD CONSTRAINT FK_1D411D77166D1F9C FOREIGN KEY (project_id) REFERENCES developtech_agility__projects (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE developtech_agility__repositories__github DROP FOREIGN KEY FK_7E6C0ED3BF396750');
        $this->addSql('ALTER TABLE beta_test_beta_tester DROP FOREIGN KEY FK_19F86C6A204E40D7');
        $this->addSql('ALTER TABLE beta_test_beta_tester DROP FOREIGN KEY FK_19F86C6A25B284CA');
        $this->addSql('DROP TABLE developtech_agility__repositories__repository');
        $this->addSql('DROP TABLE developtech_agility__repositories__github');
        $this->addSql('DROP TABLE developtech_agility__beta_testers');
        $this->addSql('DROP TABLE developtech_agility__beta_tests');
        $this->addSql('DROP TABLE beta_test_beta_tester');
        $this->addSql('ALTER TABLE developtech_agility__jobs DROP FOREIGN KEY FK_1D411D77166D1F9C');
        $this->addSql('ALTER TABLE developtech_agility__jobs ADD CONSTRAINT FK_1D411D77166D1F9C FOREIGN KEY (project_id) REFERENCES developtech_agility__projects (id)');
        $this->addSql('ALTER TABLE developtech_agility__projects ADD nbBetaTesters INT NOT NULL, ADD betaTestStatus VARCHAR(12) NOT NULL COLLATE utf8_unicode_ci');
    }
}

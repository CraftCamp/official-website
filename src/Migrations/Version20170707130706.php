<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170707130706 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE organization (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(125) NOT NULL, slug VARCHAR(125) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users__activation_link (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(80) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D6540DBAD1B862B8 (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users__user (id INT AUTO_INCREMENT NOT NULL, activation_link_id INT DEFAULT NULL, username VARCHAR(65) NOT NULL, email VARCHAR(65) NOT NULL, password VARCHAR(140) NOT NULL, salt VARCHAR(140) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', is_enabled TINYINT(1) NOT NULL, is_locked TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(2) NOT NULL, UNIQUE INDEX UNIQ_37C1021BE7927C74 (email), UNIQUE INDEX UNIQ_37C1021BB578C0D4 (activation_link_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users__member (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users__product_owner (id INT NOT NULL, organization_id INT NOT NULL, INDEX IDX_BFD444BB32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users__beta_tester (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developtech_agility__projects (id INT AUTO_INCREMENT NOT NULL, product_owner_id INT DEFAULT NULL, name VARCHAR(125) NOT NULL, slug VARCHAR(125) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, nbBetaTesters INT NOT NULL, betaTestStatus VARCHAR(12) NOT NULL, UNIQUE INDEX UNIQ_B71CBD7B5E237E06 (name), UNIQUE INDEX UNIQ_B71CBD7B989D9B62 (slug), INDEX IDX_B71CBD7BB58C0B6E (product_owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developtech_agility__jobs (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(125) NOT NULL, slug VARCHAR(125) NOT NULL, description VARCHAR(255) NOT NULL, status INT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, type VARCHAR(2) NOT NULL, INDEX IDX_1D411D77166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developtech_agility__features (id INT NOT NULL, responsible_id INT DEFAULT NULL, feature_type VARCHAR(15) NOT NULL, product_owner_value INT DEFAULT NULL, user_value INT DEFAULT NULL, INDEX IDX_544FD2CC602AD315 (responsible_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developtech_agility__feedbacks (id INT NOT NULL, author_id INT DEFAULT NULL, responsible_id INT DEFAULT NULL, INDEX IDX_68EB7FC2F675F31B (author_id), INDEX IDX_68EB7FC2602AD315 (responsible_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users__user ADD CONSTRAINT FK_37C1021BB578C0D4 FOREIGN KEY (activation_link_id) REFERENCES users__activation_link (id)');
        $this->addSql('ALTER TABLE users__member ADD CONSTRAINT FK_FF0C8513BF396750 FOREIGN KEY (id) REFERENCES users__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users__product_owner ADD CONSTRAINT FK_BFD444BB32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE users__product_owner ADD CONSTRAINT FK_BFD444BBBF396750 FOREIGN KEY (id) REFERENCES users__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users__beta_tester ADD CONSTRAINT FK_70099997BF396750 FOREIGN KEY (id) REFERENCES users__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developtech_agility__projects ADD CONSTRAINT FK_B71CBD7BB58C0B6E FOREIGN KEY (product_owner_id) REFERENCES users__user (id)');
        $this->addSql('ALTER TABLE developtech_agility__jobs ADD CONSTRAINT FK_1D411D77166D1F9C FOREIGN KEY (project_id) REFERENCES developtech_agility__projects (id)');
        $this->addSql('ALTER TABLE developtech_agility__features ADD CONSTRAINT FK_544FD2CC602AD315 FOREIGN KEY (responsible_id) REFERENCES users__user (id)');
        $this->addSql('ALTER TABLE developtech_agility__features ADD CONSTRAINT FK_544FD2CCBF396750 FOREIGN KEY (id) REFERENCES developtech_agility__jobs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developtech_agility__feedbacks ADD CONSTRAINT FK_68EB7FC2F675F31B FOREIGN KEY (author_id) REFERENCES users__user (id)');
        $this->addSql('ALTER TABLE developtech_agility__feedbacks ADD CONSTRAINT FK_68EB7FC2602AD315 FOREIGN KEY (responsible_id) REFERENCES users__user (id)');
        $this->addSql('ALTER TABLE developtech_agility__feedbacks ADD CONSTRAINT FK_68EB7FC2BF396750 FOREIGN KEY (id) REFERENCES developtech_agility__jobs (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users__product_owner DROP FOREIGN KEY FK_BFD444BB32C8A3DE');
        $this->addSql('ALTER TABLE users__user DROP FOREIGN KEY FK_37C1021BB578C0D4');
        $this->addSql('ALTER TABLE users__member DROP FOREIGN KEY FK_FF0C8513BF396750');
        $this->addSql('ALTER TABLE users__product_owner DROP FOREIGN KEY FK_BFD444BBBF396750');
        $this->addSql('ALTER TABLE users__beta_tester DROP FOREIGN KEY FK_70099997BF396750');
        $this->addSql('ALTER TABLE developtech_agility__projects DROP FOREIGN KEY FK_B71CBD7BB58C0B6E');
        $this->addSql('ALTER TABLE developtech_agility__features DROP FOREIGN KEY FK_544FD2CC602AD315');
        $this->addSql('ALTER TABLE developtech_agility__feedbacks DROP FOREIGN KEY FK_68EB7FC2F675F31B');
        $this->addSql('ALTER TABLE developtech_agility__feedbacks DROP FOREIGN KEY FK_68EB7FC2602AD315');
        $this->addSql('ALTER TABLE developtech_agility__jobs DROP FOREIGN KEY FK_1D411D77166D1F9C');
        $this->addSql('ALTER TABLE developtech_agility__features DROP FOREIGN KEY FK_544FD2CCBF396750');
        $this->addSql('ALTER TABLE developtech_agility__feedbacks DROP FOREIGN KEY FK_68EB7FC2BF396750');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE users__activation_link');
        $this->addSql('DROP TABLE users__user');
        $this->addSql('DROP TABLE users__member');
        $this->addSql('DROP TABLE users__product_owner');
        $this->addSql('DROP TABLE users__beta_tester');
        $this->addSql('DROP TABLE developtech_agility__projects');
        $this->addSql('DROP TABLE developtech_agility__jobs');
        $this->addSql('DROP TABLE developtech_agility__features');
        $this->addSql('DROP TABLE developtech_agility__feedbacks');
    }
}

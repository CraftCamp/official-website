<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180513163829 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(30) NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, type VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects__news (id INT NOT NULL, project_id INT DEFAULT NULL, INDEX IDX_D577BD75166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communities__news (id INT NOT NULL, community_id INT DEFAULT NULL, INDEX IDX_A7E8D52BFDA7B0BF (community_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projects__news ADD CONSTRAINT FK_D577BD75166D1F9C FOREIGN KEY (project_id) REFERENCES developtech_agility__projects (id)');
        $this->addSql('ALTER TABLE projects__news ADD CONSTRAINT FK_D577BD75BF396750 FOREIGN KEY (id) REFERENCES news (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE communities__news ADD CONSTRAINT FK_A7E8D52BFDA7B0BF FOREIGN KEY (community_id) REFERENCES communities__community (id)');
        $this->addSql('ALTER TABLE communities__news ADD CONSTRAINT FK_A7E8D52BBF396750 FOREIGN KEY (id) REFERENCES news (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE projects__news DROP FOREIGN KEY FK_D577BD75BF396750');
        $this->addSql('ALTER TABLE communities__news DROP FOREIGN KEY FK_A7E8D52BBF396750');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE projects__news');
        $this->addSql('DROP TABLE communities__news');
    }
}

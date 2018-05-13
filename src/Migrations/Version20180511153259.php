<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180511153259 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE communities__member (community_id INT NOT NULL, user_id INT NOT NULL, is_lead TINYINT(1) NOT NULL, joined_at DATETIME NOT NULL, INDEX IDX_C345EDC5FDA7B0BF (community_id), INDEX IDX_C345EDC5A76ED395 (user_id), PRIMARY KEY(community_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communities__project (community_id INT NOT NULL, project_id INT NOT NULL, joined_at DATETIME NOT NULL, INDEX IDX_9AD0BEC8FDA7B0BF (community_id), INDEX IDX_9AD0BEC8166D1F9C (project_id), PRIMARY KEY(community_id, project_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE communities__member ADD CONSTRAINT FK_C345EDC5FDA7B0BF FOREIGN KEY (community_id) REFERENCES communities__community (id)');
        $this->addSql('ALTER TABLE communities__member ADD CONSTRAINT FK_C345EDC5A76ED395 FOREIGN KEY (user_id) REFERENCES users__user (id)');
        $this->addSql('ALTER TABLE communities__project ADD CONSTRAINT FK_9AD0BEC8FDA7B0BF FOREIGN KEY (community_id) REFERENCES communities__community (id)');
        $this->addSql('ALTER TABLE communities__project ADD CONSTRAINT FK_9AD0BEC8166D1F9C FOREIGN KEY (project_id) REFERENCES users__user (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE communities__member');
        $this->addSql('DROP TABLE communities__project');
    }
}

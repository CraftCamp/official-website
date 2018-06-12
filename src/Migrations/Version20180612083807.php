<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180612083807 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE project__beta_testers DROP FOREIGN KEY FK_BE779174986077B');
        $this->addSql('DROP TABLE developtech_agility__beta_testers');
        $this->addSql('DROP TABLE project__repositories__github');
        $this->addSql('ALTER TABLE project__projects DROP FOREIGN KEY FK_B71CBD7BB58C0B6E');
        $this->addSql('ALTER TABLE project__projects ADD CONSTRAINT FK_636FB313B58C0B6E FOREIGN KEY (product_owner_id) REFERENCES users__product_owner (id)');
        $this->addSql('ALTER TABLE project__projects RENAME INDEX uniq_b71cbd7b5e237e06 TO UNIQ_636FB3135E237E06');
        $this->addSql('ALTER TABLE project__projects RENAME INDEX uniq_b71cbd7b989d9b62 TO UNIQ_636FB313989D9B62');
        $this->addSql('ALTER TABLE project__projects RENAME INDEX idx_b71cbd7bb58c0b6e TO IDX_636FB313B58C0B6E');
        $this->addSql('ALTER TABLE project__projects RENAME INDEX idx_b71cbd7b32c8a3de TO IDX_636FB31332C8A3DE');
        $this->addSql('ALTER TABLE project__jobs RENAME INDEX idx_1d411d77166d1f9c TO IDX_E6E31440166D1F9C');
        $this->addSql('ALTER TABLE project__features DROP FOREIGN KEY FK_544FD2CC602AD315');
        $this->addSql('DROP INDEX IDX_544FD2CC602AD315 ON project__features');
        $this->addSql('ALTER TABLE project__features DROP responsible_id');
        $this->addSql('ALTER TABLE project__beta_tests RENAME INDEX idx_ed350ed2166d1f9c TO IDX_A7A91609166D1F9C');
        $this->addSql('DROP INDEX IDX_BE779174986077B ON project__beta_testers');
        $this->addSql('ALTER TABLE project__beta_testers DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE project__beta_testers CHANGE beta_tester_model_id beta_tester_id INT NOT NULL');
        $this->addSql('ALTER TABLE project__beta_testers ADD CONSTRAINT FK_A8615F7E204E40D7 FOREIGN KEY (beta_tester_id) REFERENCES users__beta_tester (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_A8615F7E204E40D7 ON project__beta_testers (beta_tester_id)');
        $this->addSql('ALTER TABLE project__beta_testers ADD PRIMARY KEY (beta_test_id, beta_tester_id)');
        $this->addSql('ALTER TABLE project__beta_testers RENAME INDEX idx_be77917425b284ca TO IDX_A8615F7E25B284CA');
        $this->addSql('ALTER TABLE project__feedbacks DROP FOREIGN KEY FK_68EB7FC2602AD315');
        $this->addSql('DROP INDEX IDX_68EB7FC2602AD315 ON project__feedbacks');
        $this->addSql('ALTER TABLE project__feedbacks DROP responsible_id');
        $this->addSql('ALTER TABLE project__feedbacks RENAME INDEX idx_68eb7fc2f675f31b TO IDX_2B56E5A6F675F31B');
        $this->addSql('ALTER TABLE project__repositories__repository ADD name VARCHAR(255) NOT NULL, ADD slug VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP type, CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project__repositories__repository RENAME INDEX idx_351fae0b166d1f9c TO IDX_6D694AD8166D1F9C');
        $this->addSql('ALTER TABLE project__news RENAME INDEX idx_d577bd75166d1f9c TO IDX_53A3E0D5166D1F9C');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('CREATE TABLE developtech_agility__beta_testers (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D9C191609B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project__repositories__github (id INT NOT NULL, owner VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, owner_type VARCHAR(15) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developtech_agility__beta_testers ADD CONSTRAINT FK_D9C191609B6B5FBA FOREIGN KEY (account_id) REFERENCES users__user (id)');
        $this->addSql('ALTER TABLE project__repositories__github ADD CONSTRAINT FK_7E6C0ED3BF396750 FOREIGN KEY (id) REFERENCES project__repositories__repository (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project__beta_testers DROP FOREIGN KEY FK_A8615F7E204E40D7');
        $this->addSql('DROP INDEX IDX_A8615F7E204E40D7 ON project__beta_testers');
        $this->addSql('ALTER TABLE project__beta_testers DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE project__beta_testers CHANGE beta_tester_id beta_tester_model_id INT NOT NULL');
        $this->addSql('ALTER TABLE project__beta_testers ADD CONSTRAINT FK_BE779174986077B FOREIGN KEY (beta_tester_model_id) REFERENCES developtech_agility__beta_testers (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_BE779174986077B ON project__beta_testers (beta_tester_model_id)');
        $this->addSql('ALTER TABLE project__beta_testers ADD PRIMARY KEY (beta_test_id, beta_tester_model_id)');
        $this->addSql('ALTER TABLE project__beta_testers RENAME INDEX idx_a8615f7e25b284ca TO IDX_BE77917425B284CA');
        $this->addSql('ALTER TABLE project__beta_tests RENAME INDEX idx_a7a91609166d1f9c TO IDX_ED350ED2166D1F9C');
        $this->addSql('ALTER TABLE project__features ADD responsible_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project__features ADD CONSTRAINT FK_544FD2CC602AD315 FOREIGN KEY (responsible_id) REFERENCES users__user (id)');
        $this->addSql('CREATE INDEX IDX_544FD2CC602AD315 ON project__features (responsible_id)');
        $this->addSql('ALTER TABLE project__feedbacks ADD responsible_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project__feedbacks ADD CONSTRAINT FK_68EB7FC2602AD315 FOREIGN KEY (responsible_id) REFERENCES users__user (id)');
        $this->addSql('CREATE INDEX IDX_68EB7FC2602AD315 ON project__feedbacks (responsible_id)');
        $this->addSql('ALTER TABLE project__feedbacks RENAME INDEX idx_2b56e5a6f675f31b TO IDX_68EB7FC2F675F31B');
        $this->addSql('ALTER TABLE project__jobs RENAME INDEX idx_e6e31440166d1f9c TO IDX_1D411D77166D1F9C');
        $this->addSql('ALTER TABLE project__news RENAME INDEX idx_53a3e0d5166d1f9c TO IDX_D577BD75166D1F9C');
        $this->addSql('ALTER TABLE project__projects DROP FOREIGN KEY FK_636FB313B58C0B6E');
        $this->addSql('ALTER TABLE project__projects ADD CONSTRAINT FK_B71CBD7BB58C0B6E FOREIGN KEY (product_owner_id) REFERENCES users__user (id)');
        $this->addSql('ALTER TABLE project__projects RENAME INDEX uniq_636fb3135e237e06 TO UNIQ_B71CBD7B5E237E06');
        $this->addSql('ALTER TABLE project__projects RENAME INDEX uniq_636fb313989d9b62 TO UNIQ_B71CBD7B989D9B62');
        $this->addSql('ALTER TABLE project__projects RENAME INDEX idx_636fb313b58c0b6e TO IDX_B71CBD7BB58C0B6E');
        $this->addSql('ALTER TABLE project__projects RENAME INDEX idx_636fb31332c8a3de TO IDX_B71CBD7B32C8A3DE');
        $this->addSql('ALTER TABLE project__repositories__repository ADD type VARCHAR(10) NOT NULL COLLATE utf8_unicode_ci, DROP name, DROP slug, DROP created_at, DROP updated_at, CHANGE project_id project_id INT NOT NULL');
        $this->addSql('ALTER TABLE project__repositories__repository RENAME INDEX idx_6d694ad8166d1f9c TO IDX_351FAE0B166D1F9C');
    }
}

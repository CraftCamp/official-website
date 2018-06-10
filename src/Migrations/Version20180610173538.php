<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180610173538 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('RENAME TABLE developtech_agility__beta_test_users TO project__beta_testers,
            developtech_agility__beta_tests TO project__beta_tests,
            developtech_agility__features TO project__features,
            developtech_agility__feedbacks TO project__feedbacks,
            developtech_agility__jobs TO project__jobs,
            developtech_agility__projects TO project__projects,
            developtech_agility__repositories__repository TO project__repositories__repository,
            developtech_agility__repositories__github TO project__repositories__github,
            projects__news TO project__news'
        );

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE developtech_agility__beta_test_users TO project__beta_testers,
            project__beta_tests TO developtech_agility__beta_tests,
            project__features TO developtech_agility__features,
            project__feedbacks TO developtech_agility__feedbacks,
            project__jobs TO developtech_agility__jobs,
            project__projects TO developtech_agility__projects,
            project__repositories__repository TO developtech_agility__repositories__repository,
            project__repositories__github TO developtech_agility__repositories__github,
            project__news TO projects__news'
        );
    }
}

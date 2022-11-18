<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113195747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds configurable baseline configuration goals';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE baseline_configuration_goals (id INT AUTO_INCREMENT NOT NULL, baseline_configuration_id INT NOT NULL, error_goal INT NOT NULL, benefit_title VARCHAR(255) NOT NULL, benefit_description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5C23B3743EE284C3 (baseline_configuration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE baseline_configuration_goals ADD CONSTRAINT FK_5C23B3743EE284C3 FOREIGN KEY (baseline_configuration_id) REFERENCES baseline_configuration (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE baseline_configuration_goals DROP FOREIGN KEY FK_5C23B3743EE284C3');
        $this->addSql('DROP TABLE baseline_configuration_goals');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107195740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add table for saving baseline statistics';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE baseline_statistic_result (id INT AUTO_INCREMENT NOT NULL, baseline_configuration_id INT NOT NULL, commutative_errors INT NOT NULL, unique_errors INT NOT NULL, tool_version VARCHAR(255) DEFAULT NULL, commit_hash VARCHAR(255) NOT NULL, commit_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_44B30CE73EE284C3 (baseline_configuration_id), INDEX IDX_44B30CE73EE284C3A0BD6EEE (baseline_configuration_id, commit_hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE baseline_statistic_result ADD CONSTRAINT FK_44B30CE73EE284C3 FOREIGN KEY (baseline_configuration_id) REFERENCES baseline_configuration (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE baseline_statistic_result DROP FOREIGN KEY FK_44B30CE73EE284C3');
        $this->addSql('DROP TABLE baseline_statistic_result');
    }
}

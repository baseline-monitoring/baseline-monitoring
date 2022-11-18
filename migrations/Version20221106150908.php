<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106150908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change paths for parsing fields to be able to parse the configuration and the baseline together';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE baseline_configuration ADD path_to_baseline VARCHAR(255) NOT NULL, CHANGE file_path path_to_configuration VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE baseline_configuration ADD file_path VARCHAR(255) NOT NULL, DROP path_to_configuration, DROP path_to_baseline');
    }
}

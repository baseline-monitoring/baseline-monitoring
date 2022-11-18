<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113105600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relation between baseline configuration and remote server configuration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE baseline_configuration ADD remote_server_id INT NOT NULL');
        $this->addSql('UPDATE baseline_configuration SET remote_server_id = 1');
        $this->addSql('ALTER TABLE baseline_configuration ADD CONSTRAINT FK_57245D5467FD7973 FOREIGN KEY (remote_server_id) REFERENCES remote_server (id)');
        $this->addSql('CREATE INDEX IDX_57245D5467FD7973 ON baseline_configuration (remote_server_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE baseline_configuration DROP FOREIGN KEY FK_57245D5467FD7973');
        $this->addSql('DROP INDEX IDX_57245D5467FD7973 ON baseline_configuration');
        $this->addSql('ALTER TABLE baseline_configuration DROP remote_server_id');
    }
}

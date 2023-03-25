<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230325164041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds initial admin user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES (1, "admin", JSON_ARRAY("ROLE_ADMIN"), "$2y$13$yBhrekFNq03a0qNIcd78MeewdBmfejX2nwGcgjpTjeY6vABGbxVWC")');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM `user` WHERE `username` = "admin"');
    }
}

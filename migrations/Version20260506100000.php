<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260506100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove legacy base64 patient profile images and store only filenames in a VARCHAR(255) column';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE patient SET profile_image_name = NULL WHERE profile_image_name LIKE 'data:image/%'");
        $this->addSql('ALTER TABLE patient CHANGE profile_image_name profile_image_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE patient CHANGE profile_image_name profile_image_name LONGTEXT DEFAULT NULL');
    }
}

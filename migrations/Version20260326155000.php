<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Modify color column to support longer hex values
 */
final class Version20260326155000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify color column in pathology table to support longer hex values';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pathology MODIFY color VARCHAR(15) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pathology MODIFY color VARCHAR(7) DEFAULT NULL');
    }
}

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
        // Check if color column exists before adding
        $this->addSql('ALTER TABLE pathology ADD COLUMN IF NOT EXISTS color VARCHAR(15) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // Check if color column exists before dropping
        $this->addSql('ALTER TABLE pathology DROP COLUMN IF EXISTS color');
    }
}

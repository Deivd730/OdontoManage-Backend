<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add status field to pathology table
 */
final class Version20260326158000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add status field to pathology table for pending/completed management';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pathology ADD status VARCHAR(50) DEFAULT \'pending\' COMMENT \'pending or completed\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pathology DROP COLUMN status');
    }
}

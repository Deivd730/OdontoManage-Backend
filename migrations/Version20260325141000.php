<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260325141000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add infectious diseases tracking fields to patient table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE patient ADD has_infectious_diseases BOOLEAN DEFAULT false');
        $this->addSql('ALTER TABLE patient ADD infectious_diseases LONGTEXT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE patient DROP has_infectious_diseases');
        $this->addSql('ALTER TABLE patient DROP infectious_diseases');
    }
}

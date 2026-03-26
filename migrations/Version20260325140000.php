<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260325140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add medical consent fields to patient table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE patient ADD medical_treatment_consent BOOLEAN DEFAULT false');
        $this->addSql('ALTER TABLE patient ADD anesthesia_consent BOOLEAN DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE patient DROP medical_treatment_consent');
        $this->addSql('ALTER TABLE patient DROP anesthesia_consent');
    }
}

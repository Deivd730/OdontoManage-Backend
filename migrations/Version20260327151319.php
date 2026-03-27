<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260327151319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Handle partial executions safely: add only missing columns.
        $pathologyTable = $schema->getTable('pathology');
        if (!$pathologyTable->hasColumn('color')) {
            $this->addSql('ALTER TABLE pathology ADD color VARCHAR(15) DEFAULT NULL');
        }

        if (!$pathologyTable->hasColumn('status')) {
            $this->addSql('ALTER TABLE pathology ADD status VARCHAR(50) DEFAULT NULL');
        }

        // Existing data can contain NULL birth dates; normalize before setting NOT NULL.
        $this->addSql("UPDATE patient SET birth_date = COALESCE(DATE(registration_date), '2000-01-01') WHERE birth_date IS NULL");
        $this->addSql('ALTER TABLE patient CHANGE birth_date birth_date DATE NOT NULL, CHANGE medical_treatment_consent medical_treatment_consent TINYINT NOT NULL, CHANGE anesthesia_consent anesthesia_consent TINYINT NOT NULL, CHANGE has_infectious_diseases has_infectious_diseases TINYINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        if ($schema->getTable('pathology')->hasColumn('color')) {
            $this->addSql('ALTER TABLE pathology DROP color');
        }

        if ($schema->getTable('pathology')->hasColumn('status')) {
            $this->addSql('ALTER TABLE pathology DROP status');
        }

        $this->addSql('ALTER TABLE patient CHANGE birth_date birth_date DATE DEFAULT NULL, CHANGE medical_treatment_consent medical_treatment_consent TINYINT DEFAULT 0, CHANGE anesthesia_consent anesthesia_consent TINYINT DEFAULT 0, CHANGE has_infectious_diseases has_infectious_diseases TINYINT DEFAULT 0');
    }
}

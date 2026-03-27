<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260326151349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dentist ADD pathology_id INT DEFAULT NULL, DROP specialty, CHANGE available_days available_days INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dentist ADD CONSTRAINT FK_6C8FB839CE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6C8FB839CE86795D ON dentist (pathology_id)');
        $this->addSql('ALTER TABLE pathology DROP color');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY `FK_1ADAD7EB1CE0A142`');
        $this->addSql('DROP INDEX IDX_1ADAD7EB1CE0A142 ON patient');
        $this->addSql('ALTER TABLE patient DROP dentist_id, CHANGE profile_image_name profile_image_name VARCHAR(255) DEFAULT NULL, CHANGE birth_date birth_date DATE NOT NULL, CHANGE medical_treatment_consent medical_treatment_consent TINYINT NOT NULL, CHANGE anesthesia_consent anesthesia_consent TINYINT NOT NULL, CHANGE has_infectious_diseases has_infectious_diseases TINYINT NOT NULL');
        $this->addSql('ALTER TABLE treatment CHANGE duration_minutes minutes INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dentist DROP FOREIGN KEY FK_6C8FB839CE86795D');
        $this->addSql('DROP INDEX UNIQ_6C8FB839CE86795D ON dentist');
        $this->addSql('ALTER TABLE dentist ADD specialty VARCHAR(255) DEFAULT NULL, DROP pathology_id, CHANGE available_days available_days VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pathology ADD color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD dentist_id INT DEFAULT NULL, CHANGE birth_date birth_date DATE DEFAULT NULL, CHANGE medical_treatment_consent medical_treatment_consent TINYINT DEFAULT 0, CHANGE anesthesia_consent anesthesia_consent TINYINT DEFAULT 0, CHANGE has_infectious_diseases has_infectious_diseases TINYINT DEFAULT 0, CHANGE profile_image_name profile_image_name LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT `FK_1ADAD7EB1CE0A142` FOREIGN KEY (dentist_id) REFERENCES dentist (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1ADAD7EB1CE0A142 ON patient (dentist_id)');
        $this->addSql('ALTER TABLE treatment CHANGE minutes duration_minutes INT NOT NULL');
    }
}

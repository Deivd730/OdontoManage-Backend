<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260218153039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, visit_date DATETIME NOT NULL, consultation_reason LONGTEXT DEFAULT NULL, patient_id INT NOT NULL, dentist_id INT NOT NULL, box_id INT NOT NULL, treatment_id INT NOT NULL, INDEX IDX_FE38F8446B899279 (patient_id), INDEX IDX_FE38F8441CE0A142 (dentist_id), INDEX IDX_FE38F844D8177B3F (box_id), INDEX IDX_FE38F844471C0366 (treatment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE box (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, capacity INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE dentist (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, specialty VARCHAR(255) DEFAULT NULL, available_days VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_6C8FB839E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, file_url VARCHAR(255) NOT NULL, capture_date DATE NOT NULL, patient_id INT NOT NULL, INDEX IDX_D8698A766B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE pathology (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, nationnal_id VARCHAR(20) DEFAULT NULL, social_security_number VARCHAR(20) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, billing_data LONGTEXT DEFAULT NULL, health_status LONGTEXT DEFAULT NULL, family_history LONGTEXT DEFAULT NULL, lifestyle_habits LONGTEXT DEFAULT NULL, medication_allergies LONGTEXT DEFAULT NULL, registration_date DATE NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tooth (id INT AUTO_INCREMENT NOT NULL, tooth_number INT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tooth_pathology (id INT AUTO_INCREMENT NOT NULL, tooth_face INT NOT NULL, status VARCHAR(255) NOT NULL, patient_id INT NOT NULL, tooth_id INT NOT NULL, pathology_id INT NOT NULL, INDEX IDX_176392926B899279 (patient_id), INDEX IDX_17639292A2A44441 (tooth_id), INDEX IDX_17639292CE86795D (pathology_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE treatment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, duration_minutes INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8446B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8441CE0A142 FOREIGN KEY (dentist_id) REFERENCES dentist (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844D8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A766B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE tooth_pathology ADD CONSTRAINT FK_176392926B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE tooth_pathology ADD CONSTRAINT FK_17639292A2A44441 FOREIGN KEY (tooth_id) REFERENCES tooth (id)');
        $this->addSql('ALTER TABLE tooth_pathology ADD CONSTRAINT FK_17639292CE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8446B899279');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8441CE0A142');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844D8177B3F');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844471C0366');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A766B899279');
        $this->addSql('ALTER TABLE tooth_pathology DROP FOREIGN KEY FK_176392926B899279');
        $this->addSql('ALTER TABLE tooth_pathology DROP FOREIGN KEY FK_17639292A2A44441');
        $this->addSql('ALTER TABLE tooth_pathology DROP FOREIGN KEY FK_17639292CE86795D');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE box');
        $this->addSql('DROP TABLE dentist');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE pathology');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE tooth');
        $this->addSql('DROP TABLE tooth_pathology');
        $this->addSql('DROP TABLE treatment');
    }
}

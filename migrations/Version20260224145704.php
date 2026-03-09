<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migración Única: Creación de esquema e inserción de datos iniciales.
 */
final class VersionCombinedMigration extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea el esquema completo de la clínica dental e inserta 5 dentistas, 2 boxes y datos de ejemplo.';
    }

    public function up(Schema $schema): void
    {
        // --- 1. CREACIÓN DE TABLAS ---
        $this->addSql('CREATE TABLE box (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE dentist (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, specialty VARCHAR(255) DEFAULT NULL, available_days VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, box_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_6C8FB839E7927C74 (email), INDEX IDX_6C8FB839D8177B3F (box_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, national_id VARCHAR(20) NOT NULL, social_security_number VARCHAR(20) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, billing_data LONGTEXT DEFAULT NULL, health_status LONGTEXT DEFAULT NULL, family_history LONGTEXT DEFAULT NULL, lifestyle_habits LONGTEXT DEFAULT NULL, medication_allergies LONGTEXT DEFAULT NULL, registration_date DATETIME NOT NULL, profile_image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, dentist_id INT DEFAULT NULL, INDEX IDX_1ADAD7EB1CE0A142 (dentist_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE treatment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, duration_minutes INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, visit_date DATETIME NOT NULL, consultation_reason LONGTEXT DEFAULT NULL, patient_id INT NOT NULL, dentist_id INT NOT NULL, box_id INT NOT NULL, treatment_id INT NOT NULL, parent_appointment_id INT DEFAULT NULL, INDEX IDX_FE38F8446B899279 (patient_id), INDEX IDX_FE38F8441CE0A142 (dentist_id), INDEX IDX_FE38F844D8177B3F (box_id), INDEX IDX_FE38F844471C0366 (treatment_id), INDEX IDX_FE38F844FB6847F2 (parent_appointment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, file_url VARCHAR(255) DEFAULT NULL, capture_date DATE NOT NULL, updated_at DATETIME DEFAULT NULL, patient_id INT NOT NULL, INDEX IDX_D8698A766B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE pathology (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, time TIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE tooth (id INT AUTO_INCREMENT NOT NULL, tooth_number INT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE odontogram (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, appointment_id INT DEFAULT NULL, INDEX IDX_251BF9406B899279 (patient_id), INDEX IDX_251BF940E5B533F9 (appointment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE tooth_pathology (id INT AUTO_INCREMENT NOT NULL, tooth_face INT NOT NULL, status VARCHAR(255) NOT NULL, odontogram_id INT NOT NULL, tooth_id INT NOT NULL, pathology_id INT NOT NULL, INDEX IDX_1763929259C0DBCD (odontogram_id), INDEX IDX_17639292A2A44441 (tooth_id), INDEX IDX_17639292CE86795D (pathology_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');

        // --- 2. RESTRICCIONES (FOREIGN KEYS) ---
        $this->addSql('ALTER TABLE dentist ADD CONSTRAINT FK_6C8FB839D8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB1CE0A142 FOREIGN KEY (dentist_id) REFERENCES dentist (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8446B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8441CE0A142 FOREIGN KEY (dentist_id) REFERENCES dentist (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844D8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844FB6847F2 FOREIGN KEY (parent_appointment_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A766B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE odontogram ADD CONSTRAINT FK_251BF9406B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE odontogram ADD CONSTRAINT FK_251BF940E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE tooth_pathology ADD CONSTRAINT FK_1763929259C0DBCD FOREIGN KEY (odontogram_id) REFERENCES odontogram (id)');
        $this->addSql('ALTER TABLE tooth_pathology ADD CONSTRAINT FK_17639292A2A44441 FOREIGN KEY (tooth_id) REFERENCES tooth (id)');
        $this->addSql('ALTER TABLE tooth_pathology ADD CONSTRAINT FK_17639292CE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id)');

        // --- 3. INSERCIÓN DE DATOS ---

        // 2 Boxes
        $this->addSql("INSERT INTO box (id, name, status) VALUES (1, 'Box Norte', 'available'), (2, 'Box Sur', 'available')");

        // 5 Dentistas
        $this->addSql("INSERT INTO dentist (id, email, roles, password, first_name, last_name, specialty, available_days, phone, updated_at, box_id) VALUES
            (1, 'ana.garcia@clinic.local', '[]', 'hashed_pass_1', 'Ana', 'Garcia', 'Ortodoncia', 'Mon', '600111222', '2026-02-24 09:00:00', NULL),
            (2, 'luis.martin@clinic.local', '[]', 'hashed_pass_2', 'Luis', 'Martin', 'Endodoncia', 'Tue', '600333444', '2026-02-24 09:05:00', NULL),
            (3, 'marta.suarez@clinic.local', '[]', 'hashed_pass_3', 'Marta', 'Suarez', 'Protesis', 'Wed', '600555666', '2026-02-24 09:10:00', NULL),
            (4, 'pedro.alvarez@clinic.local', '[]', 'hashed_pass_4', 'Pedro', 'Alvarez', 'Implantología', 'Thu', '600777888', '2026-02-24 09:15:00', NULL),
            (5, 'laura.gomez@clinic.local', '[]', 'hashed_pass_5', 'Laura', 'Gomez', 'Odontopediatría', 'Fri', '600999000', '2026-02-24 09:20:00', NULL)
        ");

        // Tratamientos
        $this->addSql("INSERT INTO treatment (id, name, description, duration_minutes) VALUES
            (1, 'Limpieza', 'Higiene dental basica', 30),
            (2, 'Empaste', 'Restauracion de caries', 45),
            (3, 'Endodoncia', 'Tratamiento de conductos', 90)
        ");

        // Patologías
        $this->addSql("INSERT INTO pathology (id, description, time) VALUES
            (1, 'Caries', NULL), (2, 'Obturación (empaste)', NULL), (3, 'Corona', NULL), (4, 'Endodoncia', NULL),
            (5, 'Extracción', NULL), (6, 'Fractura', NULL), (7, 'Ausente', NULL), (8, 'Implante', NULL),
            (9, 'Prótesis fija', NULL), (10, 'Prótesis removible', NULL), (11, 'Puente', NULL), (12, 'Diente incluido', NULL),
            (13, 'Diente en erupción', NULL), (14, 'Sellante', NULL), (15, 'Diastema', NULL), (16, 'Apiñamiento', NULL),
            (17, 'Abrasión', NULL), (18, 'Erosión', NULL), (19, 'Abfracción', NULL), (20, 'Mancha', NULL),
            (21, 'Gingivitis', NULL), (22, 'Periodontitis', NULL), (23, 'Cálculo (sarro)', NULL), (24, 'Placa bacteriana', NULL),
            (25, 'Sensibilidad', NULL), (26, 'Movilidad', NULL), (27, 'Fístula', NULL), (28, 'Absceso', NULL),
            (29, 'Quiste', NULL), (30, 'Lesión periapical', NULL)
        ");

        // Dientes (FDI)
        $this->addSql("INSERT INTO tooth (tooth_number, description) VALUES
            (18, '3er molar sup. der.'), (17, '2do molar sup. der.'), (16, '1er molar sup. der.'), (15, '2do premolar sup. der.'), (14, '1er premolar sup. der.'), (13, 'Canino sup. der.'), (12, 'Incisivo lat. sup. der.'), (11, 'Incisivo cent. sup. der.'),
            (21, 'Incisivo cent. sup. izq.'), (22, 'Incisivo lat. sup. izq.'), (23, 'Canino sup. izq.'), (24, '1er premolar sup. izq.'), (25, '2do premolar sup. izq.'), (26, '1er molar sup. izq.'), (27, '2do molar sup. izq.'), (28, '3er molar sup. izq.'),
            (31, 'Incisivo cent. inf. izq.'), (32, 'Incisivo lat. inf. izq.'), (33, 'Canino inf. izq.'), (34, '1er premolar inf. izq.'), (35, '2do premolar inf. izq.'), (36, '1er molar inf. izq.'), (37, '2do molar inf. izq.'), (38, '3er molar inf. izq.'),
            (41, 'Incisivo cent. inf. der.'), (42, 'Incisivo lat. inf. der.'), (43, 'Canino inf. der.'), (44, '1er premolar inf. der.'), (45, '2do premolar inf. der.'), (46, '1er molar inf. der.'), (47, '2do molar inf. der.'), (48, '3er molar inf. der.')
        ");

        // Usuario Admin
        $this->addSql("INSERT INTO users (name, email, roles, password) VALUES ('admin', 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '\$2y\$13\$wF3KSdnulnp/YDCOLy982.0KIeA3NpcxI8wblWmZqsOQXVD7NOPrm')");

        // Pacientes
        $this->addSql("INSERT INTO patient (id, first_name, last_name, national_id, social_security_number, phone, email, registration_date, dentist_id) VALUES
            (1, 'Carlos', 'Lopez', '12345678A', 'SSN-001', '700111222', 'carlos.lopez@mail.local', '2026-02-24 10:00:00', 1),
            (2, 'Elena', 'Perez', '23456789B', 'SSN-002', '700333444', 'elena.perez@mail.local', '2026-02-24 10:05:00', 2),
            (3, 'Javier', 'Santos', '34567890C', 'SSN-003', '700555666', 'javier.santos@mail.local', '2026-02-24 10:10:00', 2),
            (4, 'Lucia', 'Ruiz', '45678901D', 'SSN-004', '700777888', 'lucia.ruiz@mail.local', '2026-02-24 10:15:00', 3),
            (5, 'Mario', 'Vega', '56789012E', 'SSN-005', '700999000', 'mario.vega@mail.local', '2026-02-24 10:20:00', 1),
            (6, 'Joker', 'Smith', '66789012F', 'SSN-006', '700999001', 'joker.smith@mail.local', '2026-02-24 10:20:00', 1)
        ");

        // Citas
        $this->addSql("INSERT INTO appointment (id, visit_date, consultation_reason, patient_id, dentist_id, box_id, treatment_id) VALUES
            (1, '2026-03-09 11:00:00', 'Revision general', 1, 1, 1, 1),
            (2, '2026-03-10 11:30:00', 'Dolor molar', 2, 2, 1, 3),
            (3, '2026-03-11 12:00:00', 'Caries', 3, 3, 1, 2),
            (4, '2026-03-12 12:30:00', 'Limpieza anual', 4, 4, 2, 1),
            (5, '2026-03-13 13:00:00', 'Sensibilidad dental', 5, 5, 1, 2)
        ");

        // Documentos
        $this->addSql("INSERT INTO document (type, file_url, capture_date, updated_at, patient_id) VALUES
            ('Radiografía Panorámica', 'patient_1_rx.pdf', '2026-02-25', '2026-02-25 10:00:00', 1),
            ('Consentimiento Informado', 'patient_1_consent.pdf', '2026-02-24', '2026-02-24 09:00:00', 1),
            ('Informe Médico', 'patient_2_informe.pdf', '2026-02-25', '2026-02-25 14:00:00', 2),
            ('Fotografía Intraoral', 'patient_3_foto.jpg', '2026-02-27', '2026-02-27 09:15:00', 3),
            ('Historial Clínico', 'patient_4_historial.pdf', '2026-02-27', '2026-02-27 13:00:00', 4),
            ('Plan de Tratamiento', 'patient_5_plan.pdf', '2026-02-28', '2026-02-28 15:30:00', 5)
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tooth_pathology');
        $this->addSql('DROP TABLE odontogram');
        $this->addSql('DROP TABLE tooth');
        $this->addSql('DROP TABLE pathology');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE treatment');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE dentist');
        $this->addSql('DROP TABLE box');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
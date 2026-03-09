<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224145704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, visit_date DATETIME NOT NULL, consultation_reason LONGTEXT DEFAULT NULL, patient_id INT NOT NULL, dentist_id INT NOT NULL, box_id INT NOT NULL, treatment_id INT NOT NULL, parent_appointment_id INT DEFAULT NULL, INDEX IDX_FE38F8446B899279 (patient_id), INDEX IDX_FE38F8441CE0A142 (dentist_id), INDEX IDX_FE38F844D8177B3F (box_id), INDEX IDX_FE38F844471C0366 (treatment_id), INDEX IDX_FE38F844FB6847F2 (parent_appointment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE box (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, capacity INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE dentist (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, specialty VARCHAR(255) DEFAULT NULL, available_days VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, box_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_6C8FB839E7927C74 (email), INDEX IDX_6C8FB839D8177B3F (box_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, file_url VARCHAR(255) NOT NULL, capture_date DATE NOT NULL, patient_id INT NOT NULL, INDEX IDX_D8698A766B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE odontogram (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, appointment_id INT DEFAULT NULL, INDEX IDX_251BF9406B899279 (patient_id), INDEX IDX_251BF940E5B533F9 (appointment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE pathology (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, time TIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, national_id VARCHAR(20) NOT NULL, social_security_number VARCHAR(20) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, billing_data LONGTEXT DEFAULT NULL, health_status LONGTEXT DEFAULT NULL, family_history LONGTEXT DEFAULT NULL, lifestyle_habits LONGTEXT DEFAULT NULL, medication_allergies LONGTEXT DEFAULT NULL, registration_date DATETIME NOT NULL, profile_image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, dentist_id INT DEFAULT NULL, INDEX IDX_1ADAD7EB1CE0A142 (dentist_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tooth (id INT AUTO_INCREMENT NOT NULL, tooth_number INT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tooth_pathology (id INT AUTO_INCREMENT NOT NULL, tooth_face INT NOT NULL, status VARCHAR(255) NOT NULL, odontogram_id INT NOT NULL, tooth_id INT NOT NULL, pathology_id INT NOT NULL, INDEX IDX_1763929259C0DBCD (odontogram_id), INDEX IDX_17639292A2A44441 (tooth_id), INDEX IDX_17639292CE86795D (pathology_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE treatment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, duration_minutes INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8446B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8441CE0A142 FOREIGN KEY (dentist_id) REFERENCES dentist (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844D8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844FB6847F2 FOREIGN KEY (parent_appointment_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE dentist ADD CONSTRAINT FK_6C8FB839D8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A766B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE odontogram ADD CONSTRAINT FK_251BF9406B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE odontogram ADD CONSTRAINT FK_251BF940E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB1CE0A142 FOREIGN KEY (dentist_id) REFERENCES dentist (id)');
        $this->addSql('ALTER TABLE tooth_pathology ADD CONSTRAINT FK_1763929259C0DBCD FOREIGN KEY (odontogram_id) REFERENCES odontogram (id)');
        $this->addSql('ALTER TABLE tooth_pathology ADD CONSTRAINT FK_17639292A2A44441 FOREIGN KEY (tooth_id) REFERENCES tooth (id)');
        $this->addSql('ALTER TABLE tooth_pathology ADD CONSTRAINT FK_17639292CE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id)');

        $this->addSql("INSERT INTO box (id, name, capacity, status) VALUES
            (1, 'Box Norte', 2, 'available'),
            (2, 'Box Sur', 1, 'available')
        ");

        $this->addSql("INSERT INTO dentist (id, email, roles, password, first_name, last_name, specialty, available_days, phone, updated_at, box_id) VALUES
            (1, 'ana.garcia@clinic.local', '[]', 'hashed_pass_1', 'Ana', 'Garcia', 'Ortodoncia', 'Mon,Wed,Fri', '600111222', '2026-02-24 09:00:00', 1),
            (2, 'luis.martin@clinic.local', '[]', 'hashed_pass_2', 'Luis', 'Martin', 'Endodoncia', 'Tue,Thu', '600333444', '2026-02-24 09:05:00', 1),
            (3, 'marta.suarez@clinic.local', '[]', 'hashed_pass_3', 'Marta', 'Suarez', 'Protesis', 'Mon,Thu', '600555666', '2026-02-24 09:10:00', 2)
        ");

        $this->addSql("INSERT INTO treatment (id, name, description, duration_minutes) VALUES
            (1, 'Limpieza', 'Higiene dental basica', 30),
            (2, 'Empaste', 'Restauracion de caries', 45),
            (3, 'Endodoncia', 'Tratamiento de conductos', 90)
        ");

        // Insertar patologías completas
        $this->addSql("INSERT INTO pathology (description, time) VALUES
            ('Caries', NULL),
            ('Obturación (empaste)', NULL),
            ('Corona', NULL),
            ('Endodoncia (tratamiento de conducto)', NULL),
            ('Extracción', NULL),
            ('Fractura', NULL),
            ('Ausente', NULL),
            ('Implante', NULL),
            ('Prótesis fija', NULL),
            ('Prótesis removible', NULL),
            ('Puente', NULL),
            ('Diente incluido', NULL),
            ('Diente en erupción', NULL),
            ('Sellante', NULL),
            ('Diastema', NULL),
            ('Apiñamiento', NULL),
            ('Abrasión', NULL),
            ('Erosión', NULL),
            ('Abfracción', NULL),
            ('Mancha', NULL),
            ('Gingivitis', NULL),
            ('Periodontitis', NULL),
            ('Cálculo (sarro)', NULL),
            ('Placa bacteriana', NULL),
            ('Sensibilidad', NULL),
            ('Movilidad', NULL),
            ('Fístula', NULL),
            ('Absceso', NULL),
            ('Quiste', NULL),
            ('Lesión periapical', NULL)
        ");

        // Insertar todos los dientes permanentes y temporales
        $this->addSql("INSERT INTO tooth (tooth_number, description) VALUES
            -- Dientes permanentes - Cuadrante superior derecho
            (18, 'Tercer molar superior derecho (muela del juicio)'),
            (17, 'Segundo molar superior derecho'),
            (16, 'Primer molar superior derecho'),
            (15, 'Segundo premolar superior derecho'),
            (14, 'Primer premolar superior derecho'),
            (13, 'Canino superior derecho'),
            (12, 'Incisivo lateral superior derecho'),
            (11, 'Incisivo central superior derecho'),
            -- Cuadrante superior izquierdo
            (21, 'Incisivo central superior izquierdo'),
            (22, 'Incisivo lateral superior izquierdo'),
            (23, 'Canino superior izquierdo'),
            (24, 'Primer premolar superior izquierdo'),
            (25, 'Segundo premolar superior izquierdo'),
            (26, 'Primer molar superior izquierdo'),
            (27, 'Segundo molar superior izquierdo'),
            (28, 'Tercer molar superior izquierdo (muela del juicio)'),
            -- Cuadrante inferior izquierdo
            (31, 'Incisivo central inferior izquierdo'),
            (32, 'Incisivo lateral inferior izquierdo'),
            (33, 'Canino inferior izquierdo'),
            (34, 'Primer premolar inferior izquierdo'),
            (35, 'Segundo premolar inferior izquierdo'),
            (36, 'Primer molar inferior izquierdo'),
            (37, 'Segundo molar inferior izquierdo'),
            (38, 'Tercer molar inferior izquierdo (muela del juicio)'),
            -- Cuadrante inferior derecho
            (41, 'Incisivo central inferior derecho'),
            (42, 'Incisivo lateral inferior derecho'),
            (43, 'Canino inferior derecho'),
            (44, 'Primer premolar inferior derecho'),
            (45, 'Segundo premolar inferior derecho'),
            (46, 'Primer molar inferior derecho'),
            (47, 'Segundo molar inferior derecho'),
            (48, 'Tercer molar inferior derecho (muela del juicio)'),
            -- Dientes temporales - Cuadrante superior derecho
            (55, 'Segundo molar temporal superior derecho'),
            (54, 'Primer molar temporal superior derecho'),
            (53, 'Canino temporal superior derecho'),
            (52, 'Incisivo lateral temporal superior derecho'),
            (51, 'Incisivo central temporal superior derecho'),
            -- Cuadrante superior izquierdo
            (61, 'Incisivo central temporal superior izquierdo'),
            (62, 'Incisivo lateral temporal superior izquierdo'),
            (63, 'Canino temporal superior izquierdo'),
            (64, 'Primer molar temporal superior izquierdo'),
            (65, 'Segundo molar temporal superior izquierdo'),
            -- Cuadrante inferior izquierdo
            (71, 'Incisivo central temporal inferior izquierdo'),
            (72, 'Incisivo lateral temporal inferior izquierdo'),
            (73, 'Canino temporal inferior izquierdo'),
            (74, 'Primer molar temporal inferior izquierdo'),
            (75, 'Segundo molar temporal inferior izquierdo'),
            -- Cuadrante inferior derecho
            (81, 'Incisivo central temporal inferior derecho'),
            (82, 'Incisivo lateral temporal inferior derecho'),
            (83, 'Canino temporal inferior derecho'),
            (84, 'Primer molar temporal inferior derecho'),
            (85, 'Segundo molar temporal inferior derecho')
        ");

        // Insertar usuario administrador
        $this->addSql("INSERT INTO users (name, email, roles, password) VALUES
            ('admin', 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '\$2y\$13\$wF3KSdnulnp/YDCOLy982.0KIeA3NpcxI8wblWmZqsOQXVD7NOPrm')
        ");

        $this->addSql("INSERT INTO patient (id, first_name, last_name, national_id, social_security_number, phone, email, address, billing_data, health_status, family_history, lifestyle_habits, medication_allergies, registration_date, profile_image_name, updated_at, dentist_id) VALUES
            (1, 'Carlos', 'Lopez', '12345678A', 'SSN-001', '700111222', 'carlos.lopez@mail.local', 'Calle Mayor 1', 'NIF:12345678A', 'Sin patologias', 'Sin antecedentes', 'No fumador', 'Ninguna', '2026-02-24 10:00:00', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO2pQxkAAAAASUVORK5CYII=', '2026-02-24 10:00:00', 1),
            (2, 'Elena', 'Perez', '23456789B', 'SSN-002', '700333444', 'elena.perez@mail.local', 'Avenida Sol 5', 'NIF:23456789B', 'Alergia leve', 'Diabetes', 'Sedentario', 'Penicilina', '2026-02-24 10:05:00', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO2pQxkAAAAASUVORK5CYII=', '2026-02-24 10:05:00', 2),
            (3, 'Javier', 'Santos', '34567890C', 'SSN-003', '700555666', 'javier.santos@mail.local', 'Calle Luna 3', 'NIF:34567890C', 'Hipertension', 'Hipertension', 'Ejercicio moderado', 'Ninguna', '2026-02-24 10:10:00', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO2pQxkAAAAASUVORK5CYII=', '2026-02-24 10:10:00', 2),
            (4, 'Lucia', 'Ruiz', '45678901D', 'SSN-004', '700777888', 'lucia.ruiz@mail.local', 'Plaza Norte 7', 'NIF:45678901D', 'Asma controlada', 'Asma', 'No fumador', 'Ibuprofeno', '2026-02-24 10:15:00', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO2pQxkAAAAASUVORK5CYII=', '2026-02-24 10:15:00', 3),
            (5, 'Mario', 'Vega', '56789012E', 'SSN-005', '700999000', 'mario.vega@mail.local', 'Calle Sur 9', 'NIF:56789012E', 'Sin patologias', 'Sin antecedentes', 'Fumador', 'Ninguna', '2026-02-24 10:20:00', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO2pQxkAAAAASUVORK5CYII=', '2026-02-24 10:20:00', 1),
            (6, 'Joker', 'Smith', '56789012E', 'SSN-005', '700999000', 'joker.smith@mail.local', 'Calle Sur 20', 'NIF:56789012E', 'Sin patologias', 'Sin antecedentes', 'Fumador', 'Ninguna', '2026-02-24 10:20:00', 'data:image/jpeg;base64,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', '2026-02-24 10:20:00', 1)
        ");

        $this->addSql("INSERT INTO appointment (id, visit_date, consultation_reason, patient_id, dentist_id, box_id, treatment_id, parent_appointment_id) VALUES
            (1, '2026-02-24 11:00:00', 'Revision general', 1, 1, 1, 1, NULL),
            (2, '2026-02-24 11:30:00', 'Dolor molar', 2, 2, 1, 3, NULL),
            (3, '2026-02-24 12:00:00', 'Caries', 3, 2, 1, 2, NULL),
            (4, '2026-02-24 12:30:00', 'Limpieza anual', 4, 3, 2, 1, NULL),
            (5, '2026-02-24 13:00:00', 'Sensibilidad dental', 5, 1, 1, 2, NULL)
        ");

        $this->addSql("INSERT INTO odontogram (id, patient_id, appointment_id) VALUES
            (1, 1, 1),
            (2, 2, 2),
            (3, 3, 3),
            (4, 4, 4),
            (5, 5, 5)
        ");

        $this->addSql("INSERT INTO tooth_pathology (tooth_face, status, odontogram_id, tooth_id, pathology_id) VALUES
            (1, 'active', 1, (SELECT id FROM tooth WHERE tooth_number = 11 LIMIT 1), (SELECT id FROM pathology WHERE description = 'Caries' LIMIT 1)),
            (2, 'active', 2, (SELECT id FROM tooth WHERE tooth_number = 36 LIMIT 1), (SELECT id FROM pathology WHERE description = 'Fractura' LIMIT 1)),
            (3, 'active', 3, (SELECT id FROM tooth WHERE tooth_number = 21 LIMIT 1), (SELECT id FROM pathology WHERE description = 'Caries' LIMIT 1)),
            (4, 'active', 4, (SELECT id FROM tooth WHERE tooth_number = 46 LIMIT 1), (SELECT id FROM pathology WHERE description = 'Gingivitis' LIMIT 1)),
            (5, 'active', 5, (SELECT id FROM tooth WHERE tooth_number = 11 LIMIT 1), (SELECT id FROM pathology WHERE description = 'Caries' LIMIT 1))
        ");

        $this->addSql("INSERT INTO document (id, type, file_url, capture_date, patient_id) VALUES
            (1, 'Radiografia', '/files/patient_1_rx.png', '2026-02-24', 1),
            (2, 'Fotografia', '/files/patient_2_photo.png', '2026-02-24', 2),
            (3, 'Consentimiento', '/files/patient_3_consent.pdf', '2026-02-24', 3),
            (4, 'Radiografia', '/files/patient_4_rx.png', '2026-02-24', 4),
            (5, 'Informe', '/files/patient_5_report.pdf', '2026-02-24', 5)
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8446B899279');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8441CE0A142');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844D8177B3F');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844471C0366');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844FB6847F2');
        $this->addSql('ALTER TABLE dentist DROP FOREIGN KEY FK_6C8FB839D8177B3F');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A766B899279');
        $this->addSql('ALTER TABLE odontogram DROP FOREIGN KEY FK_251BF9406B899279');
        $this->addSql('ALTER TABLE odontogram DROP FOREIGN KEY FK_251BF940E5B533F9');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB1CE0A142');
        $this->addSql('ALTER TABLE tooth_pathology DROP FOREIGN KEY FK_1763929259C0DBCD');
        $this->addSql('ALTER TABLE tooth_pathology DROP FOREIGN KEY FK_17639292A2A44441');
        $this->addSql('ALTER TABLE tooth_pathology DROP FOREIGN KEY FK_17639292CE86795D');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE box');
        $this->addSql('DROP TABLE dentist');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE odontogram');
        $this->addSql('DROP TABLE pathology');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE tooth');
        $this->addSql('DROP TABLE tooth_pathology');
        $this->addSql('DROP TABLE treatment');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

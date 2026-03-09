<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260309144020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document ADD updated_at DATETIME DEFAULT NULL, CHANGE file_url file_url VARCHAR(255) DEFAULT NULL');

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

        $this->addSql("INSERT INTO tooth (tooth_number, description) VALUES
        (18, 'Tercer molar superior derecho (muela del juicio)'),
        (17, 'Segundo molar superior derecho'),
        (16, 'Primer molar superior derecho'),
        (15, 'Segundo premolar superior derecho'),
        (14, 'Primer premolar superior derecho'),
        (13, 'Canino superior derecho'),
        (12, 'Incisivo lateral superior derecho'),
        (11, 'Incisivo central superior derecho'),
        (21, 'Incisivo central superior izquierdo'),
        (22, 'Incisivo lateral superior izquierdo'),
        (23, 'Canino superior izquierdo'),
        (24, 'Primer premolar superior izquierdo'),
        (25, 'Segundo premolar superior izquierdo'),
        (26, 'Primer molar superior izquierdo'),
        (27, 'Segundo molar superior izquierdo'),
        (28, 'Tercer molar superior izquierdo (muela del juicio)'),
        (31, 'Incisivo central inferior izquierdo'),
        (32, 'Incisivo lateral inferior izquierdo'),
        (33, 'Canino inferior izquierdo'),
        (34, 'Primer premolar inferior izquierdo'),
        (35, 'Segundo premolar inferior izquierdo'),
        (36, 'Primer molar inferior izquierdo'),
        (37, 'Segundo molar inferior izquierdo'),
        (38, 'Tercer molar inferior izquierdo (muela del juicio)'),
        (41, 'Incisivo central inferior derecho'),
        (42, 'Incisivo lateral inferior derecho'),
        (43, 'Canino inferior derecho'),
        (44, 'Primer premolar inferior derecho'),
        (45, 'Segundo premolar inferior derecho'),
        (46, 'Primer molar inferior derecho'),
        (47, 'Segundo molar inferior derecho'),
        (48, 'Tercer molar inferior derecho (muela del juicio)'),
        (55, 'Segundo molar temporal superior derecho'),
        (54, 'Primer molar temporal superior derecho'),
        (53, 'Canino temporal superior derecho'),
        (52, 'Incisivo lateral temporal superior derecho'),
        (51, 'Incisivo central temporal superior derecho'),
        (61, 'Incisivo central temporal superior izquierdo'),
        (62, 'Incisivo lateral temporal superior izquierdo'),
        (63, 'Canino temporal superior izquierdo'),
        (64, 'Primer molar temporal superior izquierdo'),
        (65, 'Segundo molar temporal superior izquierdo'),
        (71, 'Incisivo central temporal inferior izquierdo'),
        (72, 'Incisivo lateral temporal inferior izquierdo'),
        (73, 'Canino temporal inferior izquierdo'),
        (74, 'Primer molar temporal inferior izquierdo'),
        (75, 'Segundo molar temporal inferior izquierdo'),
        (81, 'Incisivo central temporal inferior derecho'),
        (82, 'Incisivo lateral temporal inferior derecho'),
        (83, 'Canino temporal inferior derecho'),
        (84, 'Primer molar temporal inferior derecho'),
        (85, 'Segundo molar temporal inferior derecho')
    ");

        $this->addSql("INSERT INTO users (name, email, roles, password) VALUES
        ('admin', 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '\$2y\$13\$wF3KSdnulnp/YDCOLy982.0KIeA3NpcxI8wblWmZqsOQXVD7NOPrm')
    ");

        $this->addSql("INSERT INTO patient (id, first_name, last_name, national_id, social_security_number, phone, email, address, billing_data, health_status, family_history, lifestyle_habits, medication_allergies, registration_date, profile_image_name, updated_at, dentist_id) VALUES
        (1, 'Carlos', 'Lopez', '12345678A', 'SSN-001', '700111222', 'carlos.lopez@mail.local', 'Calle Mayor 1', 'NIF:12345678A', 'Sin patologias', 'Sin antecedentes', 'No fumador', 'Ninguna', '2026-02-24 10:00:00', NULL, '2026-02-24 10:00:00', 1),
        (2, 'Elena', 'Perez', '23456789B', 'SSN-002', '700333444', 'elena.perez@mail.local', 'Avenida Sol 5', 'NIF:23456789B', 'Alergia leve', 'Diabetes', 'Sedentario', 'Penicilina', '2026-02-24 10:05:00', NULL, '2026-02-24 10:05:00', 2),
        (3, 'Javier', 'Santos', '34567890C', 'SSN-003', '700555666', 'javier.santos@mail.local', 'Calle Luna 3', 'NIF:34567890C', 'Hipertension', 'Hipertension', 'Ejercicio moderado', 'Ninguna', '2026-02-24 10:10:00', NULL, '2026-02-24 10:10:00', 2),
        (4, 'Lucia', 'Ruiz', '45678901D', 'SSN-004', '700777888', 'lucia.ruiz@mail.local', 'Plaza Norte 7', 'NIF:45678901D', 'Asma controlada', 'Asma', 'No fumador', 'Ibuprofeno', '2026-02-24 10:15:00', NULL, '2026-02-24 10:15:00', 3),
        (5, 'Mario', 'Vega', '56789012E', 'SSN-005', '700999000', 'mario.vega@mail.local', 'Calle Sur 9', 'NIF:56789012E', 'Sin patologias', 'Sin antecedentes', 'Fumador', 'Ninguna', '2026-02-24 10:20:00', NULL, '2026-02-24 10:20:00', 1)
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
        $this->addSql('ALTER TABLE document DROP updated_at, CHANGE file_url file_url VARCHAR(255) NOT NULL');
    }
}

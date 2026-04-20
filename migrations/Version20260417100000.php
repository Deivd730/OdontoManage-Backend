<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260417100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tooth_treatment table for tracking treatments per tooth with status';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tooth_treatment (id INT AUTO_INCREMENT NOT NULL, odontogram_id INT NOT NULL, treatment_id INT NOT NULL, tooth_number INT NOT NULL, tooth_face INT NOT NULL DEFAULT 0, status VARCHAR(20) NOT NULL DEFAULT \'pending\', PRIMARY KEY(id), INDEX IDX_TOOTH_ODONTOGRAM (odontogram_id), INDEX IDX_TOOTH_TREATMENT_TYPE (treatment_id), INDEX IDX_TOOTH_NUMBER (tooth_number), UNIQUE KEY unique_treatment_per_tooth (odontogram_id, tooth_number, treatment_id, tooth_face), CONSTRAINT FK_TOOTH_TREATMENT_ODONTOGRAM FOREIGN KEY (odontogram_id) REFERENCES odontogram (id) ON DELETE CASCADE, CONSTRAINT FK_TOOTH_TREATMENT_TYPE FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS tooth_treatment');
    }
}

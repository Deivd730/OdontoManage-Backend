<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260420100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create bridge_treatment table for puentes (dental bridges)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bridge_treatment (
            id INT AUTO_INCREMENT NOT NULL,
            odontogram_id INT NOT NULL,
            treatment_id INT NOT NULL,
            start_tooth INT NOT NULL,
            end_tooth INT NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT \'pending\',
            UNIQUE KEY unique_bridge_per_odontogram (odontogram_id, treatment_id, start_tooth, end_tooth),
            INDEX idx_odontogram (odontogram_id),
            INDEX idx_treatment (treatment_id),
            CONSTRAINT fk_bridge_odontogram FOREIGN KEY (odontogram_id) REFERENCES odontogram (id) ON DELETE CASCADE,
            CONSTRAINT fk_bridge_treatment FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS bridge_treatment');
    }
}

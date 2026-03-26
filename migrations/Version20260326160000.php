<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Update pathologies with color codes and make dynamic
 */
final class Version20260326160000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update pathologies with colors for dynamic management';
    }

    public function up(Schema $schema): void
    {
        // Delete all existing data to avoid FK conflicts
        $this->addSql('DELETE FROM tooth_pathology');
        $this->addSql('DELETE FROM pathology');
        
        // Insert new pathologies with colors
        $this->addSql("INSERT INTO pathology (description, color) VALUES 
            ('Caries', '#FF4136'),
            ('Caries', '#6f36ffff'),
            ('Obturacion', '#d96900ff'),
            ('Obturacion', '#0074D9'),
            ('Corona', '#ff5e00ff'),
            ('Corona', '#4400ffff'),
            ('Ausente', '#0c0000ff'),
            ('Endodoncia', '#420dc9ff'),
            ('Endodoncia', '#c90d0dff'),
            ('Exodoncia', '#111111ff'),
            ('Exodonciaort', '#3813f1ff'),
            ('Exodonciaort', '#761313ff'),
            ('cariesX', '#0c902fff'),
            ('fisuras', '#c2d814ff'),
            ('puente', '#0c0e01ff')
        ");
    }

    public function down(Schema $schema): void
    {
        // Delete all new pathologies
        $this->addSql('DELETE FROM tooth_pathology');
        $this->addSql('DELETE FROM pathology');
        
        // Reinsert old pathologies without colors
        $this->addSql("INSERT INTO pathology (description, minutes) VALUES
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
        
        // Drop color column
        $this->addSql('ALTER TABLE pathology DROP COLUMN color');
    }
}

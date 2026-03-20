<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260320164500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add patient birth date and odontogram type (adult/child).';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE patient ADD birth_date DATE DEFAULT NULL');
        $this->addSql("ALTER TABLE odontogram ADD type VARCHAR(20) NOT NULL DEFAULT 'adult'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE odontogram DROP type');
        $this->addSql('ALTER TABLE patient DROP birth_date');
    }
}

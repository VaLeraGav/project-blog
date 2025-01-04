<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250104144008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE blog ADD COLUMN created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD COLUMN updated_at DATETIME DEFAULT NULL');

        $this->addSql('ALTER TABLE category ADD COLUMN created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD COLUMN updated_at DATETIME DEFAULT NULL');

        $this->addSql('ALTER TABLE tag ADD COLUMN created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD COLUMN updated_at DATETIME DEFAULT NULL');

        $this->addSql('ALTER TABLE user ADD COLUMN created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE blog DROP created_at');
        $this->addSql('ALTER TABLE blog DROP updated_at');

        $this->addSql('ALTER TABLE category DROP created_at');
        $this->addSql('ALTER TABLE category DROP updated_at');

        $this->addSql('ALTER TABLE tag DROP created_at');
        $this->addSql('ALTER TABLE tag DROP updated_at');

        $this->addSql('ALTER TABLE user DROP created_at');
        $this->addSql('ALTER TABLE user DROP updated_at');
    }
}

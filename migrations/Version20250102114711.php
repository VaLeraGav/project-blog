<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250102114711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE blog');
        $this->addSql('CREATE TABLE blog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, text CLOB NOT NULL, description CLOB NOT NULL, CONSTRAINT FK_C015514312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C0155143A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE blog');
    }
}

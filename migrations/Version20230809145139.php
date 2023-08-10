<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230809145139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_competenze_bis (categorie_id INT NOT NULL, competenze_bis_id INT NOT NULL, INDEX IDX_513766E6BCF5E72D (categorie_id), INDEX IDX_513766E6C9D698A8 (competenze_bis_id), PRIMARY KEY(categorie_id, competenze_bis_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_competenze_bis ADD CONSTRAINT FK_513766E6BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_competenze_bis ADD CONSTRAINT FK_513766E6C9D698A8 FOREIGN KEY (competenze_bis_id) REFERENCES competenze_bis (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_competenze_bis DROP FOREIGN KEY FK_513766E6BCF5E72D');
        $this->addSql('ALTER TABLE categorie_competenze_bis DROP FOREIGN KEY FK_513766E6C9D698A8');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_competenze_bis');
    }
}

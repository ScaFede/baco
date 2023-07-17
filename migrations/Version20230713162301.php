<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713162301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competenze_bis (id INT AUTO_INCREMENT NOT NULL, titolo VARCHAR(255) NOT NULL, descrizione LONGTEXT NOT NULL, status_active TINYINT(1) DEFAULT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competenze_bis_user (competenze_bis_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7CF4249EC9D698A8 (competenze_bis_id), INDEX IDX_7CF4249EA76ED395 (user_id), PRIMARY KEY(competenze_bis_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competenze_bis_user ADD CONSTRAINT FK_7CF4249EC9D698A8 FOREIGN KEY (competenze_bis_id) REFERENCES competenze_bis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competenze_bis_user ADD CONSTRAINT FK_7CF4249EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competenze_bis_user DROP FOREIGN KEY FK_7CF4249EC9D698A8');
        $this->addSql('ALTER TABLE competenze_bis_user DROP FOREIGN KEY FK_7CF4249EA76ED395');
        $this->addSql('DROP TABLE competenze_bis');
        $this->addSql('DROP TABLE competenze_bis_user');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230804134931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scambi (id INT AUTO_INCREMENT NOT NULL, status LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scambi_user (scambi_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_132748F1D43497B5 (scambi_id), INDEX IDX_132748F1A76ED395 (user_id), PRIMARY KEY(scambi_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scambi_user ADD CONSTRAINT FK_132748F1D43497B5 FOREIGN KEY (scambi_id) REFERENCES scambi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scambi_user ADD CONSTRAINT FK_132748F1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scambi_user DROP FOREIGN KEY FK_132748F1D43497B5');
        $this->addSql('ALTER TABLE scambi_user DROP FOREIGN KEY FK_132748F1A76ED395');
        $this->addSql('DROP TABLE scambi');
        $this->addSql('DROP TABLE scambi_user');
    }
}

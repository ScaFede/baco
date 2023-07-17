<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712135304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competenze ADD user_rel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE competenze ADD CONSTRAINT FK_FD4C1672B58BAF0 FOREIGN KEY (user_rel_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FD4C1672B58BAF0 ON competenze (user_rel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competenze DROP FOREIGN KEY FK_FD4C1672B58BAF0');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_FD4C1672B58BAF0 ON competenze');
        $this->addSql('ALTER TABLE competenze DROP user_rel_id');
    }
}

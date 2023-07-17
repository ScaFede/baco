<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713122743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE homepage (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competenze_user DROP FOREIGN KEY FK_384D054FA76ED395');
        $this->addSql('ALTER TABLE competenze_user DROP FOREIGN KEY FK_384D054F7886E858');
        $this->addSql('DROP TABLE competenze_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competenze_user (competenze_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_384D054F7886E858 (competenze_id), INDEX IDX_384D054FA76ED395 (user_id), PRIMARY KEY(competenze_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE competenze_user ADD CONSTRAINT FK_384D054FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competenze_user ADD CONSTRAINT FK_384D054F7886E858 FOREIGN KEY (competenze_id) REFERENCES competenze (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE homepage');
    }
}

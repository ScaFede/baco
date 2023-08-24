<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822095943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scambi ADD user_target_competenza_rel_id INT DEFAULT NULL, ADD user_sender_competenza_rel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scambi ADD CONSTRAINT FK_42D6CB9D9FC177F4 FOREIGN KEY (user_target_competenza_rel_id) REFERENCES competenze_bis (id)');
        $this->addSql('ALTER TABLE scambi ADD CONSTRAINT FK_42D6CB9D45297A1 FOREIGN KEY (user_sender_competenza_rel_id) REFERENCES competenze_bis (id)');
        $this->addSql('CREATE INDEX IDX_42D6CB9D9FC177F4 ON scambi (user_target_competenza_rel_id)');
        $this->addSql('CREATE INDEX IDX_42D6CB9D45297A1 ON scambi (user_sender_competenza_rel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scambi DROP FOREIGN KEY FK_42D6CB9D9FC177F4');
        $this->addSql('ALTER TABLE scambi DROP FOREIGN KEY FK_42D6CB9D45297A1');
        $this->addSql('DROP INDEX IDX_42D6CB9D9FC177F4 ON scambi');
        $this->addSql('DROP INDEX IDX_42D6CB9D45297A1 ON scambi');
        $this->addSql('ALTER TABLE scambi DROP user_target_competenza_rel_id, DROP user_sender_competenza_rel_id');
    }
}

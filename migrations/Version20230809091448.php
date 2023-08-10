<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230809091448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scambi ADD scambio_confermato TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE scambi ADD CONSTRAINT FK_42D6CB9DF6C43E79 FOREIGN KEY (user_sender_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_42D6CB9DF6C43E79 ON scambi (user_sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scambi DROP FOREIGN KEY FK_42D6CB9DF6C43E79');
        $this->addSql('DROP INDEX IDX_42D6CB9DF6C43E79 ON scambi');
        $this->addSql('ALTER TABLE scambi DROP scambio_confermato');
    }
}

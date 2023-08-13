<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230813145531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD citta_rel_id INT DEFAULT NULL, DROP city');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496B795E04 FOREIGN KEY (citta_rel_id) REFERENCES citta (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6496B795E04 ON user (citta_rel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496B795E04');
        $this->addSql('DROP INDEX IDX_8D93D6496B795E04 ON user');
        $this->addSql('ALTER TABLE user ADD city VARCHAR(255) DEFAULT NULL, DROP citta_rel_id');
    }
}

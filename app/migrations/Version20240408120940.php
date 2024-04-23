<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408120940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_message ADD initiator_id INT NOT NULL');
        $this->addSql('ALTER TABLE player_message ADD CONSTRAINT FK_D749CF3A7DB3B714 FOREIGN KEY (initiator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D749CF3A7DB3B714 ON player_message (initiator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_message DROP FOREIGN KEY FK_D749CF3A7DB3B714');
        $this->addSql('DROP INDEX IDX_D749CF3A7DB3B714 ON player_message');
        $this->addSql('ALTER TABLE player_message DROP initiator_id');
    }
}

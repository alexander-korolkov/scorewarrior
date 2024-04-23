<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408120420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_grant ADD initiator_id INT NOT NULL');
        $this->addSql('ALTER TABLE item_grant ADD CONSTRAINT FK_E3A27D9C7DB3B714 FOREIGN KEY (initiator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E3A27D9C7DB3B714 ON item_grant (initiator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_grant DROP FOREIGN KEY FK_E3A27D9C7DB3B714');
        $this->addSql('DROP INDEX IDX_E3A27D9C7DB3B714 ON item_grant');
        $this->addSql('ALTER TABLE item_grant DROP initiator_id');
    }
}

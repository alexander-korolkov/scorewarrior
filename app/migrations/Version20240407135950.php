<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407135950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_grant (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_grant_item (item_grant_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_6032CE7430014138 (item_grant_id), INDEX IDX_6032CE74126F525E (item_id), PRIMARY KEY(item_grant_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_grant_item ADD CONSTRAINT FK_6032CE7430014138 FOREIGN KEY (item_grant_id) REFERENCES item_grant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_grant_item ADD CONSTRAINT FK_6032CE74126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_grant_item DROP FOREIGN KEY FK_6032CE7430014138');
        $this->addSql('ALTER TABLE item_grant_item DROP FOREIGN KEY FK_6032CE74126F525E');
        $this->addSql('DROP TABLE item_grant');
        $this->addSql('DROP TABLE item_grant_item');
    }
}

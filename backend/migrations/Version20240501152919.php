<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501152919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_networks (id UUID NOT NULL, user_id UUID NOT NULL, network VARCHAR(255) NOT NULL, identity VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3934502BA76ED395 ON user_networks (user_id)');
        $this->addSql('ALTER TABLE user_networks ADD CONSTRAINT FK_3934502BA76ED395 FOREIGN KEY (user_id) REFERENCES user_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_network DROP CONSTRAINT fk_388999b6a76ed395');
        $this->addSql('DROP TABLE user_network');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE user_network (id UUID NOT NULL, user_id UUID NOT NULL, network VARCHAR(255) NOT NULL, identity VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_388999b6a76ed395 ON user_network (user_id)');
        $this->addSql('ALTER TABLE user_network ADD CONSTRAINT fk_388999b6a76ed395 FOREIGN KEY (user_id) REFERENCES user_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_networks DROP CONSTRAINT FK_3934502BA76ED395');
        $this->addSql('DROP TABLE user_networks');
    }
}

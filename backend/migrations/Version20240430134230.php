<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240430134230 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_network (id UUID NOT NULL, user_id UUID NOT NULL, network VARCHAR(255) NOT NULL, identity VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_388999B6A76ED395 ON user_network (user_id)');
        $this->addSql('ALTER TABLE user_network ADD CONSTRAINT FK_388999B6A76ED395 FOREIGN KEY (user_id) REFERENCES user_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE network DROP CONSTRAINT fk_608487bca76ed395');
        $this->addSql('DROP TABLE network');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE network (id UUID NOT NULL, user_id UUID NOT NULL, network VARCHAR(255) NOT NULL, identity VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_608487bca76ed395 ON network (user_id)');
        $this->addSql('ALTER TABLE network ADD CONSTRAINT fk_608487bca76ed395 FOREIGN KEY (user_id) REFERENCES user_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_network DROP CONSTRAINT FK_388999B6A76ED395');
        $this->addSql('DROP TABLE user_network');
    }
}

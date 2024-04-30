<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240430134505 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_f6415eb17adf3dfb');
        $this->addSql('ALTER TABLE user_users RENAME COLUMN email_email TO email');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1E7927C74 ON user_users (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_F6415EB1E7927C74');
        $this->addSql('ALTER TABLE user_users RENAME COLUMN email TO email_email');
        $this->addSql('CREATE UNIQUE INDEX uniq_f6415eb17adf3dfb ON user_users (email_email)');
    }
}

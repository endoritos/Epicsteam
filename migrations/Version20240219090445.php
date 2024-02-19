<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219090445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE friendships ADD status VARCHAR(20) NOT NULL DEFAULT 'pending'");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('DROP TABLE message');
        $this->addSql('ALTER TABLE friendships DROP FOREIGN KEY FK_E0A8B7CAED442CF4');
        $this->addSql('ALTER TABLE friendships DROP FOREIGN KEY FK_E0A8B7CA2261B4C3');
        $this->addSql('DROP INDEX IDX_E0A8B7CAED442CF4 ON friendships');
        $this->addSql('DROP INDEX IDX_E0A8B7CA2261B4C3 ON friendships');
        $this->addSql('ALTER TABLE friendships CHANGE status status VARCHAR(20) DEFAULT \'pending\' NOT NULL');
    }
}

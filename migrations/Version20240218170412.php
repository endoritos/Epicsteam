<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218170412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friendships DROP status');
        $this->addSql('ALTER TABLE friendships ADD CONSTRAINT FK_E0A8B7CAED442CF4 FOREIGN KEY (requester_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friendships ADD CONSTRAINT FK_E0A8B7CA2261B4C3 FOREIGN KEY (addressee_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E0A8B7CAED442CF4 ON friendships (requester_id)');
        $this->addSql('CREATE INDEX IDX_E0A8B7CA2261B4C3 ON friendships (addressee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friendships DROP FOREIGN KEY FK_E0A8B7CAED442CF4');
        $this->addSql('ALTER TABLE friendships DROP FOREIGN KEY FK_E0A8B7CA2261B4C3');
        $this->addSql('DROP INDEX IDX_E0A8B7CAED442CF4 ON friendships');
        $this->addSql('DROP INDEX IDX_E0A8B7CA2261B4C3 ON friendships');
        $this->addSql('ALTER TABLE friendships ADD status VARCHAR(20) NOT NULL');
    }
}

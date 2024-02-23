<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221082746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE friendships CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE user ADD is_admin TINYINT(1) NOT NULL, ADD is_blocked TINYINT(1) NOT NULL, ADD can_send_messages TINYINT(1) NOT NULL, ADD can_send_friend_requests TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE friendships CHANGE status status VARCHAR(20) DEFAULT \'pending\' NOT NULL');
        $this->addSql('ALTER TABLE user DROP is_admin, DROP is_blocked, DROP can_send_messages, DROP can_send_friend_requests');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friendships CHANGE status status VARCHAR(20) DEFAULT \'pending\' NOT NULL');
        $this->addSql('ALTER TABLE user DROP is_admin, DROP is_blocked, DROP can_send_messages, DROP can_send_friend_requests');
    }
}

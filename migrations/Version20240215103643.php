<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215103643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_game DROP FOREIGN KEY FK_8A2F7F134584665A');
        $this->addSql('ALTER TABLE user_game DROP FOREIGN KEY FK_8A2F7F13772E836AF396750CCC410E5');
        $this->addSql('DROP TABLE user_game');
        $this->addSql('ALTER TABLE game ADD image_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E48FD905 ON user (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_game (user_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_8A2F7F134584665A (game_id), INDEX IDX_8A2F7F13772E836AF396750CCC410E5 (user_id), PRIMARY KEY(user_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_game ADD CONSTRAINT FK_8A2F7F134584665A FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE user_game ADD CONSTRAINT FK_8A2F7F13772E836AF396750CCC410E5 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game DROP image_path');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E48FD905');
        $this->addSql('DROP INDEX IDX_8D93D649E48FD905 ON user');
        $this->addSql('ALTER TABLE user DROP game_id');
    }
}

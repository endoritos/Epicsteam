<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215141954 extends AbstractMigration
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
        $this->addSql('ALTER TABLE game ADD user_id INT NOT NULL, ADD game_name VARCHAR(255) NOT NULL, ADD game_api VARCHAR(255) NOT NULL, DROP gamename, DROP gameapi');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_232B318CA76ED395 ON game (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E48FD905');
        $this->addSql('DROP INDEX IDX_8D93D649E48FD905 ON user');
        $this->addSql('ALTER TABLE user DROP game_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_game (user_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_8A2F7F13772E836AF396750CCC410E5 (user_id), INDEX IDX_8A2F7F134584665A (game_id), PRIMARY KEY(user_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_game ADD CONSTRAINT FK_8A2F7F134584665A FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE user_game ADD CONSTRAINT FK_8A2F7F13772E836AF396750CCC410E5 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CA76ED395');
        $this->addSql('DROP INDEX IDX_232B318CA76ED395 ON game');
        $this->addSql('ALTER TABLE game ADD gamename VARCHAR(255) NOT NULL, ADD gameapi VARCHAR(255) NOT NULL, DROP user_id, DROP game_name, DROP game_api');
        $this->addSql('ALTER TABLE user ADD game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E48FD905 ON user (game_id)');
    }
}

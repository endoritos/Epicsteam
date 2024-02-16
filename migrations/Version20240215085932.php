<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215085932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    // this up() migration is auto-generated, please modify it to your needs
    $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, gamename VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, gameapi VARCHAR(255) NOT NULL, created DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    $this->addSql('CREATE TABLE user_game (user_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_8A2F7F13772E836AF396750CCC410E5 (user_id), INDEX IDX_8A2F7F134584665A (game_id), PRIMARY KEY(user_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE user_game ADD CONSTRAINT FK_8A2F7F13772E836AF396750CCC410E5 FOREIGN KEY (user_id) REFERENCES user (id)');
    $this->addSql('ALTER TABLE user_game ADD CONSTRAINT FK_8A2F7F134584665A FOREIGN KEY (game_id) REFERENCES game (id)');
}


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64997FFC673');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP INDEX IDX_8D93D64997FFC673 ON user');
        $this->addSql('ALTER TABLE user DROP games_id');
    }
}

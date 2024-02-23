<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223200932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achievement DROP FOREIGN KEY FK_96737FF1E48FD905');
        $this->addSql('DROP INDEX IDX_96737FF1E48FD905 ON achievement');
        $this->addSql('ALTER TABLE achievement DROP game_id');

        $this->addSql("INSERT INTO achievement (achievement_name,) VALUES ('Game Creator', 'https://cdn2.unrealengine.com/the-game-awards-2023-3-3840x2160-a60bbb379cfb.png' )");
        $this->addSql("INSERT INTO achievement (achievement_name,) VALUES ('First Game Played', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSTnOfzQbN0K8O14vVknEWST6c9mFU1-zi8qw&usqp=CAU')");
        $this->addSql("INSERT INTO achievement (achievement_name,) VALUES ('High Score Achiever', 'https://www.lalibre.be/resizer/AkhcqXTVbZJM7mK0uDaeb8iHT8w=/1200x800/filters:format(jpeg):focal(465x240:475x230)/cloudfront-eu-central-1.images.arcpublishing.com/ipmgroup/SF4FKKKOXZDNTNJCWID3YYOEKI.jpg')");
    }
    
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achievement ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE achievement ADD CONSTRAINT FK_96737FF1E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_96737FF1E48FD905 ON achievement (game_id)');
    }
}

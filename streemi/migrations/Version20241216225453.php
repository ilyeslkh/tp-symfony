<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20241216225453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category ADD name VARCHAR(100) NOT NULL, DROP nom, DROP icon, CHANGE label label VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C40C86FCE');
        $this->addSql('DROP INDEX IDX_9474526C40C86FCE ON comment');
        $this->addSql('ALTER TABLE comment CHANGE publisher_id user_id INT NOT NULL, CHANGE status status_enum VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('ALTER TABLE episode ADD release_date DATE NOT NULL, DROP released_at, CHANGE season_id season_id INT DEFAULT NULL, CHANGE duration duration TIME NOT NULL');
        $this->addSql('ALTER TABLE language ADD name VARCHAR(100) NOT NULL, DROP nom, CHANGE code code VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE media DROP popularity, CHANGE short_description short_description LONGTEXT DEFAULT NULL, CHANGE long_description long_description LONGTEXT DEFAULT NULL, CHANGE release_date release_date DATE NOT NULL, CHANGE cover_image cover_image VARCHAR(255) DEFAULT NULL, CHANGE staff staff JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE casting casting JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE discr disc VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112D61220EA6');
        $this->addSql('DROP INDEX IDX_D782112D61220EA6 ON playlist');
        $this->addSql('ALTER TABLE playlist DROP creator_id, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE playlist_subscription DROP FOREIGN KEY FK_832940C7808B1AD');
        $this->addSql('DROP INDEX IDX_832940C7808B1AD ON playlist_subscription');
        $this->addSql('ALTER TABLE playlist_subscription CHANGE subscriber_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE playlist_subscription ADD CONSTRAINT FK_832940CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_832940CA76ED395 ON playlist_subscription (user_id)');
        $this->addSql('ALTER TABLE season ADD season_number INT NOT NULL, DROP number, CHANGE serie_id serie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ADD description VARCHAR(255) DEFAULT NULL, ADD details LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE subscription_history DROP FOREIGN KEY FK_54AF90D07808B1AD');
        $this->addSql('DROP INDEX IDX_54AF90D07808B1AD ON subscription_history');
        $this->addSql('ALTER TABLE subscription_history ADD start_date DATE NOT NULL, ADD end_date DATE NOT NULL, DROP start_at, DROP end_at, CHANGE subscriber_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE subscription_history ADD CONSTRAINT FK_54AF90D0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_54AF90D0A76ED395 ON subscription_history (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL, CHANGE account_status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE watch_history DROP FOREIGN KEY FK_DE44EFD8C300AB5D');
        $this->addSql('DROP INDEX IDX_DE44EFD8C300AB5D ON watch_history');
        $this->addSql('ALTER TABLE watch_history ADD last_watched DATETIME NOT NULL, DROP last_watched_at, CHANGE media_id media_id INT DEFAULT NULL, CHANGE watcher_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE watch_history ADD CONSTRAINT FK_DE44EFD8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_DE44EFD8A76ED395 ON watch_history (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD nom VARCHAR(255) NOT NULL, ADD icon VARCHAR(255) NOT NULL, DROP name, CHANGE label label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE user_id publisher_id INT NOT NULL, CHANGE status_enum status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C40C86FCE FOREIGN KEY (publisher_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526C40C86FCE ON comment (publisher_id)');
        $this->addSql('ALTER TABLE episode ADD released_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP release_date, CHANGE season_id season_id INT NOT NULL, CHANGE duration duration INT NOT NULL');
        $this->addSql('ALTER TABLE language ADD nom VARCHAR(255) NOT NULL, DROP name, CHANGE code code VARCHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE media ADD popularity INT NOT NULL, CHANGE short_description short_description LONGTEXT NOT NULL, CHANGE long_description long_description LONGTEXT NOT NULL, CHANGE release_date release_date DATETIME NOT NULL, CHANGE cover_image cover_image VARCHAR(255) NOT NULL, CHANGE staff staff JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE casting casting JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE disc discr VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE playlist ADD creator_id INT NOT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D782112D61220EA6 ON playlist (creator_id)');
        $this->addSql('ALTER TABLE playlist_subscription DROP FOREIGN KEY FK_832940CA76ED395');
        $this->addSql('DROP INDEX IDX_832940CA76ED395 ON playlist_subscription');
        $this->addSql('ALTER TABLE playlist_subscription CHANGE user_id subscriber_id INT NOT NULL');
        $this->addSql('ALTER TABLE playlist_subscription ADD CONSTRAINT FK_832940C7808B1AD FOREIGN KEY (subscriber_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_832940C7808B1AD ON playlist_subscription (subscriber_id)');
        $this->addSql('ALTER TABLE season ADD number VARCHAR(255) NOT NULL, DROP season_number, CHANGE serie_id serie_id INT NOT NULL');
        $this->addSql('ALTER TABLE subscription DROP description, DROP details');
        $this->addSql('ALTER TABLE subscription_history DROP FOREIGN KEY FK_54AF90D0A76ED395');
        $this->addSql('DROP INDEX IDX_54AF90D0A76ED395 ON subscription_history');
        $this->addSql('ALTER TABLE subscription_history ADD start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP start_date, DROP end_date, CHANGE user_id subscriber_id INT NOT NULL');
        $this->addSql('ALTER TABLE subscription_history ADD CONSTRAINT FK_54AF90D07808B1AD FOREIGN KEY (subscriber_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_54AF90D07808B1AD ON subscription_history (subscriber_id)');
        $this->addSql('ALTER TABLE `user` DROP reset_token, CHANGE status account_status VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON `user` (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON `user` (email)');
        $this->addSql('ALTER TABLE watch_history DROP FOREIGN KEY FK_DE44EFD8A76ED395');
        $this->addSql('DROP INDEX IDX_DE44EFD8A76ED395 ON watch_history');
        $this->addSql('ALTER TABLE watch_history ADD last_watched_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP last_watched, CHANGE media_id media_id INT NOT NULL, CHANGE user_id watcher_id INT NOT NULL');
        $this->addSql('ALTER TABLE watch_history ADD CONSTRAINT FK_DE44EFD8C300AB5D FOREIGN KEY (watcher_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DE44EFD8C300AB5D ON watch_history (watcher_id)');
    }
}

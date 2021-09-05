<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905081140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apikey (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, api_key VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B84757A1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, train_id INT NOT NULL, number VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, comments VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_773DE69D23BCD4D0 (train_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locomotive (id INT AUTO_INCREMENT NOT NULL, type_and_number VARCHAR(255) NOT NULL, painting VARCHAR(255) NOT NULL, short_name VARCHAR(255) DEFAULT NULL, owner VARCHAR(255) NOT NULL, comments VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, scheme VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station_user (station_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_63A0F17421BDB235 (station_id), INDEX IDX_63A0F174A76ED395 (user_id), PRIMARY KEY(station_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station_log (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, station_id INT NOT NULL, date DATE NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_B27979C7A76ED395 (user_id), INDEX IDX_B27979C721BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE to_do_list (id INT AUTO_INCREMENT NOT NULL, deadline DATE DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, added_by_admin TINYINT(1) NOT NULL, done TINYINT(1) NOT NULL, display TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE to_do_list_user (to_do_list_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_217390F1B3AB48EB (to_do_list_id), INDEX IDX_217390F1A76ED395 (user_id), PRIMARY KEY(to_do_list_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE train (id INT AUTO_INCREMENT NOT NULL, locomotive_id INT NOT NULL, station_id INT NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5C66E4A3587009A8 (locomotive_id), INDEX IDX_5C66E4A321BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE train_log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, train_id INT NOT NULL, date DATE NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_621B6E56A76ED395 (user_id), INDEX IDX_621B6E5623BCD4D0 (train_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, forget_password_hash VARCHAR(255) DEFAULT NULL, profile_photo VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apikey ADD CONSTRAINT FK_B84757A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D23BCD4D0 FOREIGN KEY (train_id) REFERENCES train (id)');
        $this->addSql('ALTER TABLE station_user ADD CONSTRAINT FK_63A0F17421BDB235 FOREIGN KEY (station_id) REFERENCES station (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE station_user ADD CONSTRAINT FK_63A0F174A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE station_log ADD CONSTRAINT FK_B27979C7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE station_log ADD CONSTRAINT FK_B27979C721BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE to_do_list_user ADD CONSTRAINT FK_217390F1B3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE to_do_list_user ADD CONSTRAINT FK_217390F1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE train ADD CONSTRAINT FK_5C66E4A3587009A8 FOREIGN KEY (locomotive_id) REFERENCES locomotive (id)');
        $this->addSql('ALTER TABLE train ADD CONSTRAINT FK_5C66E4A321BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE train_log ADD CONSTRAINT FK_621B6E56A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE train_log ADD CONSTRAINT FK_621B6E5623BCD4D0 FOREIGN KEY (train_id) REFERENCES train (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE train DROP FOREIGN KEY FK_5C66E4A3587009A8');
        $this->addSql('ALTER TABLE station_user DROP FOREIGN KEY FK_63A0F17421BDB235');
        $this->addSql('ALTER TABLE station_log DROP FOREIGN KEY FK_B27979C721BDB235');
        $this->addSql('ALTER TABLE train DROP FOREIGN KEY FK_5C66E4A321BDB235');
        $this->addSql('ALTER TABLE to_do_list_user DROP FOREIGN KEY FK_217390F1B3AB48EB');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D23BCD4D0');
        $this->addSql('ALTER TABLE train_log DROP FOREIGN KEY FK_621B6E5623BCD4D0');
        $this->addSql('ALTER TABLE apikey DROP FOREIGN KEY FK_B84757A1A76ED395');
        $this->addSql('ALTER TABLE station_user DROP FOREIGN KEY FK_63A0F174A76ED395');
        $this->addSql('ALTER TABLE station_log DROP FOREIGN KEY FK_B27979C7A76ED395');
        $this->addSql('ALTER TABLE to_do_list_user DROP FOREIGN KEY FK_217390F1A76ED395');
        $this->addSql('ALTER TABLE train_log DROP FOREIGN KEY FK_621B6E56A76ED395');
        $this->addSql('DROP TABLE apikey');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE locomotive');
        $this->addSql('DROP TABLE station');
        $this->addSql('DROP TABLE station_user');
        $this->addSql('DROP TABLE station_log');
        $this->addSql('DROP TABLE to_do_list');
        $this->addSql('DROP TABLE to_do_list_user');
        $this->addSql('DROP TABLE train');
        $this->addSql('DROP TABLE train_log');
        $this->addSql('DROP TABLE user');
    }
}

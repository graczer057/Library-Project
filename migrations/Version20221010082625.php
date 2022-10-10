<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221010082625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reader (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, reservations_quantity INT NOT NULL, UNIQUE INDEX UNIQ_CC3F893C9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rent (id INT AUTO_INCREMENT NOT NULL, reservation_id_id INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, expire_date DATETIME NOT NULL, is_returned TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_2784DCC3C3B4EF0 (reservation_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, book_id_id INT DEFAULT NULL, reader_id_id INT DEFAULT NULL, is_rented TINYINT(1) NOT NULL, INDEX IDX_42C8495571868B2E (book_id_id), INDEX IDX_42C84955ACFE02D (reader_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', is_active TINYINT(1) NOT NULL, is_banned TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reader ADD CONSTRAINT FK_CC3F893C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCC3C3B4EF0 FOREIGN KEY (reservation_id_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495571868B2E FOREIGN KEY (book_id_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955ACFE02D FOREIGN KEY (reader_id_id) REFERENCES reader (id)');
        $this->addSql('ALTER TABLE admins DROP FOREIGN KEY FK_A2E0150F9D86650F');
        $this->addSql('ALTER TABLE readers DROP FOREIGN KEY FK_34AD8C059D86650F');
        $this->addSql('ALTER TABLE rents DROP FOREIGN KEY FK_89DE39DD3C3B4EF0');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23971868B2E');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239ACFE02D');
        $this->addSql('DROP TABLE admins');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE readers');
        $this->addSql('DROP TABLE rents');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admins (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, surname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_A2E0150F9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, author VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE readers (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, surname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, reservations_quantity INT NOT NULL, UNIQUE INDEX UNIQ_34AD8C059D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rents (id INT AUTO_INCREMENT NOT NULL, reservation_id_id INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, expire_date DATETIME NOT NULL, is_returned TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_89DE39DD3C3B4EF0 (reservation_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, reader_id_id INT DEFAULT NULL, book_id_id INT DEFAULT NULL, is_rented TINYINT(1) NOT NULL, INDEX IDX_4DA23971868B2E (book_id_id), INDEX IDX_4DA239ACFE02D (reader_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, login VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_active TINYINT(1) NOT NULL, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', is_banned TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE admins ADD CONSTRAINT FK_A2E0150F9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE readers ADD CONSTRAINT FK_34AD8C059D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE rents ADD CONSTRAINT FK_89DE39DD3C3B4EF0 FOREIGN KEY (reservation_id_id) REFERENCES reservations (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23971868B2E FOREIGN KEY (book_id_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239ACFE02D FOREIGN KEY (reader_id_id) REFERENCES readers (id)');
        $this->addSql('ALTER TABLE reader DROP FOREIGN KEY FK_CC3F893C9D86650F');
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCC3C3B4EF0');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495571868B2E');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955ACFE02D');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE reader');
        $this->addSql('DROP TABLE rent');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE user');
    }
}

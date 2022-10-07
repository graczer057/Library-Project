<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007105528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rents (id INT AUTO_INCREMENT NOT NULL, reservation_id_id INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, expire_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_89DE39DD3C3B4EF0 (reservation_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rents ADD CONSTRAINT FK_89DE39DD3C3B4EF0 FOREIGN KEY (reservation_id_id) REFERENCES reservations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rents DROP FOREIGN KEY FK_89DE39DD3C3B4EF0');
        $this->addSql('DROP TABLE rents');
    }
}

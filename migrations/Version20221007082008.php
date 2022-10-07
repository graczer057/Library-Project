<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007082008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2399D86650F');
        $this->addSql('DROP INDEX IDX_4DA2399D86650F ON reservations');
        $this->addSql('ALTER TABLE reservations CHANGE user_id_id reader_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239ACFE02D FOREIGN KEY (reader_id_id) REFERENCES readers (id)');
        $this->addSql('CREATE INDEX IDX_4DA239ACFE02D ON reservations (reader_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239ACFE02D');
        $this->addSql('DROP INDEX IDX_4DA239ACFE02D ON reservations');
        $this->addSql('ALTER TABLE reservations CHANGE reader_id_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2399D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_4DA2399D86650F ON reservations (user_id_id)');
    }
}

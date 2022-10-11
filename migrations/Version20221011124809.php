<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221011124809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCC3C3B4EF0');
        $this->addSql('DROP INDEX UNIQ_2784DCC3C3B4EF0 ON rent');
        $this->addSql('ALTER TABLE rent ADD reader_id_id INT DEFAULT NULL, CHANGE reservation_id_id book_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCC71868B2E FOREIGN KEY (book_id_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCACFE02D FOREIGN KEY (reader_id_id) REFERENCES reader (id)');
        $this->addSql('CREATE INDEX IDX_2784DCC71868B2E ON rent (book_id_id)');
        $this->addSql('CREATE INDEX IDX_2784DCCACFE02D ON rent (reader_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCC71868B2E');
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCCACFE02D');
        $this->addSql('DROP INDEX IDX_2784DCC71868B2E ON rent');
        $this->addSql('DROP INDEX IDX_2784DCCACFE02D ON rent');
        $this->addSql('ALTER TABLE rent ADD reservation_id_id INT DEFAULT NULL, DROP book_id_id, DROP reader_id_id');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCC3C3B4EF0 FOREIGN KEY (reservation_id_id) REFERENCES reservation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2784DCC3C3B4EF0 ON rent (reservation_id_id)');
    }
}

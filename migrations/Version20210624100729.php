<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624100729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casual_client ADD resonsable_of_record_id INT NOT NULL');
        $this->addSql('ALTER TABLE casual_client ADD CONSTRAINT FK_175DB6E026C557DC FOREIGN KEY (resonsable_of_record_id) REFERENCES responsable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_175DB6E026C557DC ON casual_client (resonsable_of_record_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE casual_client DROP CONSTRAINT FK_175DB6E026C557DC');
        $this->addSql('DROP INDEX IDX_175DB6E026C557DC');
        $this->addSql('ALTER TABLE casual_client DROP resonsable_of_record_id');
    }
}

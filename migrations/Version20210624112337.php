<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624112337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casual_client DROP CONSTRAINT fk_175db6e026c557dc');
        $this->addSql('DROP INDEX idx_175db6e026c557dc');
        $this->addSql('ALTER TABLE casual_client RENAME COLUMN resonsable_of_record_id TO responsable_of_record_id');
        $this->addSql('ALTER TABLE casual_client ADD CONSTRAINT FK_175DB6E05E2D8285 FOREIGN KEY (responsable_of_record_id) REFERENCES responsable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_175DB6E05E2D8285 ON casual_client (responsable_of_record_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE casual_client DROP CONSTRAINT FK_175DB6E05E2D8285');
        $this->addSql('DROP INDEX IDX_175DB6E05E2D8285');
        $this->addSql('ALTER TABLE casual_client RENAME COLUMN responsable_of_record_id TO resonsable_of_record_id');
        $this->addSql('ALTER TABLE casual_client ADD CONSTRAINT fk_175db6e026c557dc FOREIGN KEY (resonsable_of_record_id) REFERENCES responsable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_175db6e026c557dc ON casual_client (resonsable_of_record_id)');
    }
}

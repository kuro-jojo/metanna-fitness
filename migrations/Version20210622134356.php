<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210622134356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE amount_setting ADD amount_register INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amount_setting ADD reduction_register INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amount_setting ADD amount_subs INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amount_setting ADD reduction_subs INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amount_setting DROP register_amount');
        $this->addSql('ALTER TABLE amount_setting DROP register_reduction');
        $this->addSql('ALTER TABLE amount_setting DROP subs_amount');
        $this->addSql('ALTER TABLE amount_setting DROP subs_reduction');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE amount_setting ADD register_amount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amount_setting ADD register_reduction INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amount_setting ADD subs_amount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amount_setting ADD subs_reduction INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amount_setting DROP amount_register');
        $this->addSql('ALTER TABLE amount_setting DROP reduction_register');
        $this->addSql('ALTER TABLE amount_setting DROP amount_subs');
        $this->addSql('ALTER TABLE amount_setting DROP reduction_subs');
    }
}

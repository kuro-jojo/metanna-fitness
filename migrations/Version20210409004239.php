<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409004239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add relation Between Client and Subscription';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription ADD subscribed_client_id INT NOT NULL');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3E9BD840B FOREIGN KEY (subscribed_client_id) REFERENCES client (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A3C664D3E9BD840B ON subscription (subscribed_client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3E9BD840B');
        $this->addSql('DROP INDEX UNIQ_A3C664D3E9BD840B ON subscription');
        $this->addSql('ALTER TABLE subscription DROP subscribed_client_id');
    }
}

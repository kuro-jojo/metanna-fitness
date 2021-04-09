<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409003955 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add relation Between Client and Registration';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7BB4574AF FOREIGN KEY (registred_client_id) REFERENCES client (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62A8A7A7BB4574AF ON registration (registred_client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7BB4574AF');
        $this->addSql('DROP INDEX UNIQ_62A8A7A7BB4574AF ON registration');
    }
}

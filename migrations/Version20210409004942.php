<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409004942 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add relation between client and ClientCard and between Client and ClientActivities';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD my_card_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455C035360C FOREIGN KEY (my_card_id) REFERENCES client_card (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455C035360C ON client (my_card_id)');
        $this->addSql('ALTER TABLE client_activities ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE client_activities ADD CONSTRAINT FK_25A592A419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_25A592A419EB6921 ON client_activities (client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455C035360C');
        $this->addSql('DROP INDEX UNIQ_C7440455C035360C ON client');
        $this->addSql('ALTER TABLE client DROP my_card_id');
        $this->addSql('ALTER TABLE client_activities DROP FOREIGN KEY FK_25A592A419EB6921');
        $this->addSql('DROP INDEX IDX_25A592A419EB6921 ON client_activities');
        $this->addSql('ALTER TABLE client_activities DROP client_id');
    }
}

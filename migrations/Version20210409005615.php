<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409005615 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add relation Between User and Registration between User and Subscription';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration ADD responsable_of_registration_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A778A2E2AE FOREIGN KEY (responsable_of_registration_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A778A2E2AE ON registration (responsable_of_registration_id)');
        $this->addSql('ALTER TABLE subscription ADD responsable_of_subs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D31127BC76 FOREIGN KEY (responsable_of_subs_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A3C664D31127BC76 ON subscription (responsable_of_subs_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A778A2E2AE');
        $this->addSql('DROP INDEX IDX_62A8A7A778A2E2AE ON registration');
        $this->addSql('ALTER TABLE registration DROP responsable_of_registration_id');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D31127BC76');
        $this->addSql('DROP INDEX IDX_A3C664D31127BC76 ON subscription');
        $this->addSql('ALTER TABLE subscription DROP responsable_of_subs_id');
    }
}

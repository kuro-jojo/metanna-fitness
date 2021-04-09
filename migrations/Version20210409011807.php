<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409011807 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add relation Between User and Article and Sales Article and Sales';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales ADD responsable_of_sale_id INT NOT NULL, ADD article_sold_id INT NOT NULL');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B81704443224AD9 FOREIGN KEY (responsable_of_sale_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B817044251A0ACA FOREIGN KEY (article_sold_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_6B81704443224AD9 ON sales (responsable_of_sale_id)');
        $this->addSql('CREATE INDEX IDX_6B817044251A0ACA ON sales (article_sold_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales DROP FOREIGN KEY FK_6B81704443224AD9');
        $this->addSql('ALTER TABLE sales DROP FOREIGN KEY FK_6B817044251A0ACA');
        $this->addSql('DROP INDEX IDX_6B81704443224AD9 ON sales');
        $this->addSql('DROP INDEX IDX_6B817044251A0ACA ON sales');
        $this->addSql('ALTER TABLE sales DROP responsable_of_sale_id, DROP article_sold_id');
    }
}

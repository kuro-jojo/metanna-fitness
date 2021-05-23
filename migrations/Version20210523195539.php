<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210523195539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, responsable_of_sale_id INT NOT NULL, article_sold_id INT NOT NULL, date_of_sale DATE NOT NULL, previous_stock INT NOT NULL, quantity_sold INT NOT NULL, INDEX IDX_E54BC00543224AD9 (responsable_of_sale_id), INDEX IDX_E54BC005251A0ACA (article_sold_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC00543224AD9 FOREIGN KEY (responsable_of_sale_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005251A0ACA FOREIGN KEY (article_sold_id) REFERENCES article (id)');
        $this->addSql('DROP TABLE sales');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sales (id INT AUTO_INCREMENT NOT NULL, responsable_of_sale_id INT NOT NULL, article_sold_id INT NOT NULL, date_of_sale DATE NOT NULL, previous_stock INT NOT NULL, quantity_sold INT NOT NULL, INDEX IDX_6B817044251A0ACA (article_sold_id), INDEX IDX_6B81704443224AD9 (responsable_of_sale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B817044251A0ACA FOREIGN KEY (article_sold_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B81704443224AD9 FOREIGN KEY (responsable_of_sale_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE sale');
    }
}

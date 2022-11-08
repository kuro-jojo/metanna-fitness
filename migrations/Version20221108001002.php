<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221108001002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE article_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE casual_client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_activities_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_card_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE daily_clients_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE registration_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reset_password_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE responsable_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sale_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE settings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE subscription_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE article (id INT NOT NULL, category_id INT NOT NULL, label VARCHAR(255) NOT NULL, price INT NOT NULL, stock INT NOT NULL, image_file_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66EA750E8 ON article (label)');
        $this->addSql('CREATE INDEX IDX_23A0E6612469DE2 ON article (category_id)');
        $this->addSql('CREATE TABLE casual_client (id INT NOT NULL, responsable_of_record_id INT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, amount INT NOT NULL, done_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_175DB6E05E2D8285 ON casual_client (responsable_of_record_id)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, my_card_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, profile_file_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455450FF010 ON client (telephone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455C035360C ON client (my_card_id)');
        $this->addSql('CREATE TABLE client_activities (id INT NOT NULL, client_id INT NOT NULL, date_of_activity DATE NOT NULL, state_of_client VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25A592A419EB6921 ON client_activities (client_id)');
        $this->addSql('CREATE TABLE client_card (id INT NOT NULL, bar_code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE daily_clients (id INT NOT NULL, date_of_presence DATE NOT NULL, amount_pay INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE registration (id INT NOT NULL, registered_client_id INT NOT NULL, responsable_of_registration_id INT DEFAULT NULL, date_of_registration DATE NOT NULL, amount_of_registration INT NOT NULL, deadline DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62A8A7A74307E1F ON registration (registered_client_id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A778A2E2AE ON registration (responsable_of_registration_id)');
        $this->addSql('CREATE TABLE reset_password_request (id INT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE responsable (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN NOT NULL, profile_file_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_52520D07E7927C74 ON responsable (email)');
        $this->addSql('CREATE TABLE sale (id INT NOT NULL, responsable_of_sale_id INT NOT NULL, article_sold_id INT NOT NULL, date_of_sale DATE NOT NULL, previous_stock INT NOT NULL, quantity_sold INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E54BC00543224AD9 ON sale (responsable_of_sale_id)');
        $this->addSql('CREATE INDEX IDX_E54BC005251A0ACA ON sale (article_sold_id)');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, responsable_id INT NOT NULL, date_of_service TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, service_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E19D9AD253C59D72 ON service (responsable_id)');
        $this->addSql('CREATE TABLE settings (id INT NOT NULL, default_registration_amount INT NOT NULL, default_subs_amount INT NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E545A0C577153098 ON settings (code)');
        $this->addSql('CREATE TABLE subscription (id INT NOT NULL, subscribed_client_id INT NOT NULL, responsable_of_subs_id INT DEFAULT NULL, start_of_subs DATE NOT NULL, end_of_subs DATE NOT NULL, amount_of_subs INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A3C664D3E9BD840B ON subscription (subscribed_client_id)');
        $this->addSql('CREATE INDEX IDX_A3C664D31127BC76 ON subscription (responsable_of_subs_id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE casual_client ADD CONSTRAINT FK_175DB6E05E2D8285 FOREIGN KEY (responsable_of_record_id) REFERENCES responsable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455C035360C FOREIGN KEY (my_card_id) REFERENCES client_card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_activities ADD CONSTRAINT FK_25A592A419EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A74307E1F FOREIGN KEY (registered_client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A778A2E2AE FOREIGN KEY (responsable_of_registration_id) REFERENCES responsable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES responsable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC00543224AD9 FOREIGN KEY (responsable_of_sale_id) REFERENCES responsable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005251A0ACA FOREIGN KEY (article_sold_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD253C59D72 FOREIGN KEY (responsable_id) REFERENCES responsable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3E9BD840B FOREIGN KEY (subscribed_client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D31127BC76 FOREIGN KEY (responsable_of_subs_id) REFERENCES responsable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE article_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE casual_client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_activities_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_card_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE daily_clients_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE registration_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reset_password_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE responsable_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sale_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE settings_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE subscription_id_seq CASCADE');
        $this->addSql('ALTER TABLE article DROP CONSTRAINT FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE casual_client DROP CONSTRAINT FK_175DB6E05E2D8285');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C7440455C035360C');
        $this->addSql('ALTER TABLE client_activities DROP CONSTRAINT FK_25A592A419EB6921');
        $this->addSql('ALTER TABLE registration DROP CONSTRAINT FK_62A8A7A74307E1F');
        $this->addSql('ALTER TABLE registration DROP CONSTRAINT FK_62A8A7A778A2E2AE');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE sale DROP CONSTRAINT FK_E54BC00543224AD9');
        $this->addSql('ALTER TABLE sale DROP CONSTRAINT FK_E54BC005251A0ACA');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD253C59D72');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D3E9BD840B');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D31127BC76');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE casual_client');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_activities');
        $this->addSql('DROP TABLE client_card');
        $this->addSql('DROP TABLE daily_clients');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE responsable');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE subscription');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231203155252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create client, account, transaction tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE account (id INT NOT NULL, client_id INT NOT NULL, uuid UUID NOT NULL, currency VARCHAR(3) NOT NULL, balance NUMERIC(32, 16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4D17F50A6 ON account (uuid)');
        $this->addSql('CREATE INDEX IDX_7D3656A419EB6921 ON account (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A419EB69216956883F ON account (client_id, currency)');
        $this->addSql('COMMENT ON COLUMN account.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN account.balance IS \'(DC2Type:php_decimal)\'');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455D17F50A6 ON client (uuid)');
        $this->addSql('COMMENT ON COLUMN client.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, account_id INT NOT NULL, uuid UUID NOT NULL, currency VARCHAR(3) NOT NULL, amount NUMERIC(32, 16) NOT NULL, transaction_type VARCHAR(8) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_723705D1D17F50A6 ON transaction (uuid)');
        $this->addSql('CREATE INDEX IDX_723705D19B6B5FBA ON transaction (account_id)');
        $this->addSql('COMMENT ON COLUMN transaction.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.amount IS \'(DC2Type:php_decimal)\'');
        $this->addSql('COMMENT ON COLUMN transaction.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A419EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE account_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transaction_id_seq CASCADE');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_7D3656A419EB6921');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D19B6B5FBA');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE transaction');
    }
}

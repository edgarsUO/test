<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231201083717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create rates table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE rate (id INT NOT NULL, base VARCHAR(3) NOT NULL, currency VARCHAR(3) NOT NULL, value NUMERIC(32, 16) NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN rate.value IS \'(DC2Type:php_decimal)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DFEC3F396956883F ON rate (currency)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE rate_id_seq CASCADE');
        $this->addSql('DROP TABLE rate');
    }
}

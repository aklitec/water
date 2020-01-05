<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191229111828 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE water_meter ADD bill_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE water_meter ADD CONSTRAINT FK_A9F325471A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A9F325471A8C12F5 ON water_meter (bill_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE water_meter DROP FOREIGN KEY FK_A9F325471A8C12F5');
        $this->addSql('DROP INDEX UNIQ_A9F325471A8C12F5 ON water_meter');
        $this->addSql('ALTER TABLE water_meter DROP bill_id');
    }
}

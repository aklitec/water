<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191113103556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consumption ADD water_meter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE consumption ADD CONSTRAINT FK_2CFF2DF9DFD32D9D FOREIGN KEY (water_meter_id) REFERENCES water_meter (id)');
        $this->addSql('CREATE INDEX IDX_2CFF2DF9DFD32D9D ON consumption (water_meter_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consumption DROP FOREIGN KEY FK_2CFF2DF9DFD32D9D');
        $this->addSql('DROP INDEX IDX_2CFF2DF9DFD32D9D ON consumption');
        $this->addSql('ALTER TABLE consumption DROP water_meter_id');
    }
}

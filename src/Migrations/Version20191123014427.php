<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191123014427 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(25) NOT NULL, last_name VARCHAR(25) NOT NULL, cin VARCHAR(10) NOT NULL, phone_number DOUBLE PRECISION NOT NULL, full_name VARCHAR(50) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, createdAt DATETIME NOT NULL, deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE water_meter (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, code CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', setup_date DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, wm_number VARCHAR(10) NOT NULL, deleted TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A9F3254777153098 (code), INDEX IDX_A9F3254719EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consumption (id INT AUTO_INCREMENT NOT NULL, water_meter_id INT DEFAULT NULL, code CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', date DATETIME NOT NULL, previous_record INT NOT NULL, current_record INT NOT NULL, consumption INT NOT NULL, cost NUMERIC(7, 2) NOT NULL, status SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, cost_per_meter_cube SMALLINT NOT NULL, month SMALLINT NOT NULL, deleted TINYINT(1) NOT NULL, INDEX IDX_2CFF2DF9DFD32D9D (water_meter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', full_name VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, deleted TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_1483A5E9A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_1483A5E9C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, water_meter_id INT DEFAULT NULL, street_address VARCHAR(100) NOT NULL, city VARCHAR(25) NOT NULL, suit VARCHAR(50) DEFAULT NULL, zip_code INT NOT NULL, UNIQUE INDEX UNIQ_D4E6F8119EB6921 (client_id), UNIQUE INDEX UNIQ_D4E6F81DFD32D9D (water_meter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE water_meter ADD CONSTRAINT FK_A9F3254719EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE consumption ADD CONSTRAINT FK_2CFF2DF9DFD32D9D FOREIGN KEY (water_meter_id) REFERENCES water_meter (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81DFD32D9D FOREIGN KEY (water_meter_id) REFERENCES water_meter (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE water_meter DROP FOREIGN KEY FK_A9F3254719EB6921');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8119EB6921');
        $this->addSql('ALTER TABLE consumption DROP FOREIGN KEY FK_2CFF2DF9DFD32D9D');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81DFD32D9D');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE water_meter');
        $this->addSql('DROP TABLE consumption');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE address');
    }
}

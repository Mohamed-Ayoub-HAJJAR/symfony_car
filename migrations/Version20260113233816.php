<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260113233816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car_feature (car_id INT NOT NULL, feature_id INT NOT NULL, INDEX IDX_4C7C8EBAC3C6F69F (car_id), INDEX IDX_4C7C8EBA60E4B879 (feature_id), PRIMARY KEY (car_id, feature_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE feature (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE car_feature ADD CONSTRAINT FK_4C7C8EBAC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_feature ADD CONSTRAINT FK_4C7C8EBA60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car_feature DROP FOREIGN KEY FK_4C7C8EBAC3C6F69F');
        $this->addSql('ALTER TABLE car_feature DROP FOREIGN KEY FK_4C7C8EBA60E4B879');
        $this->addSql('DROP TABLE car_feature');
        $this->addSql('DROP TABLE feature');
    }
}

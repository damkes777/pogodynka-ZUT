<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008154509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__weather_history AS SELECT id, location_id_id, temperature, unit, created_at FROM weather_history');
        $this->addSql('DROP TABLE weather_history');
        $this->addSql('CREATE TABLE weather_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, location_id INTEGER NOT NULL, temperature DOUBLE PRECISION NOT NULL, unit VARCHAR(255) NOT NULL, created_at DATE NOT NULL, CONSTRAINT FK_8385930064D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO weather_history (id, location_id, temperature, unit, created_at) SELECT id, location_id_id, temperature, unit, created_at FROM __temp__weather_history');
        $this->addSql('DROP TABLE __temp__weather_history');
        $this->addSql('CREATE INDEX IDX_8385930064D218E ON weather_history (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__weather_history AS SELECT id, location_id, temperature, unit, created_at FROM weather_history');
        $this->addSql('DROP TABLE weather_history');
        $this->addSql('CREATE TABLE weather_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, location_id_id INTEGER NOT NULL, temperature DOUBLE PRECISION NOT NULL, unit VARCHAR(255) NOT NULL, created_at DATE NOT NULL, CONSTRAINT FK_83859300918DB72 FOREIGN KEY (location_id_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO weather_history (id, location_id_id, temperature, unit, created_at) SELECT id, location_id, temperature, unit, created_at FROM __temp__weather_history');
        $this->addSql('DROP TABLE __temp__weather_history');
        $this->addSql('CREATE INDEX IDX_83859300918DB72 ON weather_history (location_id_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200921171808 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD login_name VARCHAR(255) NOT NULL, ADD surname VARCHAR(255) NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD middlename VARCHAR(255) NOT NULL, ADD birthday DATE DEFAULT NULL, ADD phone INT NOT NULL, ADD activate TINYINT(1) DEFAULT \'1\' NOT NULL, ADD hidden TINYINT(1) DEFAULT \'0\' NOT NULL, ADD creation_time DATETIME DEFAULT NULL, ADD updatetime DATETIME DEFAULT NULL, ADD visit_time DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP login_name, DROP surname, DROP name, DROP middlename, DROP birthday, DROP phone, DROP activate, DROP hidden, DROP creation_time, DROP updatetime, DROP visit_time');
    }
}

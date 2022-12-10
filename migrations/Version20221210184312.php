<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210184312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE find CHANGE latitude latitude LONGTEXT DEFAULT NULL, CHANGE longitude longitude LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE image CHANGE url url LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE find CHANGE latitude latitude LONGTEXT NOT NULL, CHANGE longitude longitude LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE url url LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}

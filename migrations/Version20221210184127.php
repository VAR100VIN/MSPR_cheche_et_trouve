<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210184127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE find DROP FOREIGN KEY FK_C9AE6404A76ED395');
        $this->addSql('ALTER TABLE find CHANGE user_id user_id INT NOT NULL, CHANGE latitude latitude LONGTEXT DEFAULT NULL, CHANGE longitude longitude LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE find ADD CONSTRAINT FK_C9AE6404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image CHANGE url url LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE find DROP FOREIGN KEY FK_C9AE6404A76ED395');
        $this->addSql('ALTER TABLE find CHANGE user_id user_id INT DEFAULT NULL, CHANGE latitude latitude LONGTEXT NOT NULL, CHANGE longitude longitude LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE find ADD CONSTRAINT FK_C9AE6404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE image CHANGE url url LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}

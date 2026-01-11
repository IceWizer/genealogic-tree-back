<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111203712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person CHANGE id id BINARY(16) NOT NULL, CHANGE parent1_id parent1_id BINARY(16) DEFAULT NULL, CHANGE parent2_id parent2_id BINARY(16) DEFAULT NULL, CHANGE owner_id owner_id BINARY(16) NOT NULL, CHANGE firstnames first_names VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE id id BINARY(16) NOT NULL, CHANGE email_verified_at email_verified_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE wedding CHANGE id id BINARY(16) NOT NULL, CHANGE person1_id person1_id BINARY(16) NOT NULL, CHANGE person2_id person2_id BINARY(16) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE parent1_id parent1_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE parent2_id parent2_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE owner_id owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE first_names firstnames VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE email_verified_at email_verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE wedding CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE person1_id person1_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE person2_id person2_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }
}

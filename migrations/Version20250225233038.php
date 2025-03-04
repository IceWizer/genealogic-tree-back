<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225233038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', parent1_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', parent2_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, firstnames VARCHAR(255) NOT NULL, birthname VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL, birth_certificate INT DEFAULT NULL, death_date DATE DEFAULT NULL, death_certificate INT DEFAULT NULL, INDEX IDX_34DCD176861B2665 (parent1_id), INDEX IDX_34DCD17694AE898B (parent2_id), INDEX IDX_34DCD1767E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email_verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', token VARCHAR(350) DEFAULT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wedding (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', person1_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', person2_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', wedding_date DATE DEFAULT NULL, wedding_certificate INT DEFAULT NULL, divorsed_date DATE NOT NULL, divorsed_certificate INT DEFAULT NULL, INDEX IDX_5BC25C963EF5821B (person1_id), INDEX IDX_5BC25C962C402DF5 (person2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176861B2665 FOREIGN KEY (parent1_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17694AE898B FOREIGN KEY (parent2_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD1767E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wedding ADD CONSTRAINT FK_5BC25C963EF5821B FOREIGN KEY (person1_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE wedding ADD CONSTRAINT FK_5BC25C962C402DF5 FOREIGN KEY (person2_id) REFERENCES person (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176861B2665');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17694AE898B');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD1767E3C61F9');
        $this->addSql('ALTER TABLE wedding DROP FOREIGN KEY FK_5BC25C963EF5821B');
        $this->addSql('ALTER TABLE wedding DROP FOREIGN KEY FK_5BC25C962C402DF5');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wedding');
    }
}

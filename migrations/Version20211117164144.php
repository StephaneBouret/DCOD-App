<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211117164144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE index_words (id INT AUTO_INCREMENT NOT NULL, alphabet_id INT NOT NULL, title VARCHAR(255) NOT NULL, page VARCHAR(255) NOT NULL, tome INT NOT NULL, level VARCHAR(255) NOT NULL, INDEX IDX_83B49E1A86D95EE5 (alphabet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE index_words ADD CONSTRAINT FK_83B49E1A86D95EE5 FOREIGN KEY (alphabet_id) REFERENCES alphabet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE index_words');
    }
}

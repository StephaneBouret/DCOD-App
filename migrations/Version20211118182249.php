<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118182249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post ADD category_post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D8C514352 FOREIGN KEY (category_post_id) REFERENCES category_blog (id)');
        $this->addSql('CREATE INDEX IDX_BA5AE01D8C514352 ON blog_post (category_post_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D8C514352');
        $this->addSql('DROP INDEX IDX_BA5AE01D8C514352 ON blog_post');
        $this->addSql('ALTER TABLE blog_post DROP category_post_id');
    }
}

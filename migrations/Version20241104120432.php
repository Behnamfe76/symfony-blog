<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104120432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE approvals (id SERIAL NOT NULL, changed_by_id INT DEFAULT NULL, post_id INT NOT NULL, approved_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, changed_to VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B7A4D6DE828AD0A0 ON approvals (changed_by_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B7A4D6DE4B89032C ON approvals (post_id)');
        $this->addSql('COMMENT ON COLUMN approvals.approved_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN approvals.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN approvals.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE post_categories (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN post_categories.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN post_categories.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE post_types (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE posts (id SERIAL NOT NULL, author_id INT NOT NULL, post_type_id INT NOT NULL, post_category_id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT DEFAULT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_885DBAFAF675F31B ON posts (author_id)');
        $this->addSql('CREATE INDEX IDX_885DBAFAF8A43BA0 ON posts (post_type_id)');
        $this->addSql('CREATE INDEX IDX_885DBAFAFE0617CD ON posts (post_category_id)');
        $this->addSql('COMMENT ON COLUMN posts.published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN posts.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN posts.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "users" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "users" (email)');
        $this->addSql('COMMENT ON COLUMN "users".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "users".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE approvals ADD CONSTRAINT FK_B7A4D6DE828AD0A0 FOREIGN KEY (changed_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE approvals ADD CONSTRAINT FK_B7A4D6DE4B89032C FOREIGN KEY (post_id) REFERENCES posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAF675F31B FOREIGN KEY (author_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAF8A43BA0 FOREIGN KEY (post_type_id) REFERENCES post_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAFE0617CD FOREIGN KEY (post_category_id) REFERENCES post_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE approvals DROP CONSTRAINT FK_B7A4D6DE828AD0A0');
        $this->addSql('ALTER TABLE approvals DROP CONSTRAINT FK_B7A4D6DE4B89032C');
        $this->addSql('ALTER TABLE posts DROP CONSTRAINT FK_885DBAFAF675F31B');
        $this->addSql('ALTER TABLE posts DROP CONSTRAINT FK_885DBAFAF8A43BA0');
        $this->addSql('ALTER TABLE posts DROP CONSTRAINT FK_885DBAFAFE0617CD');
        $this->addSql('DROP TABLE approvals');
        $this->addSql('DROP TABLE post_categories');
        $this->addSql('DROP TABLE post_types');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE "users"');
    }
}

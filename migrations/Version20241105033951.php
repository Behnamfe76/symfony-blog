<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105033951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_b7a4d6de828ad0a0');
        $this->addSql('CREATE INDEX IDX_B7A4D6DE828AD0A0 ON approvals (changed_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_B7A4D6DE828AD0A0');
        $this->addSql('CREATE UNIQUE INDEX uniq_b7a4d6de828ad0a0 ON approvals (changed_by_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214122802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formules_category (formules_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_4793CDB9168F3793 (formules_id), INDEX IDX_4793CDB912469DE2 (category_id), PRIMARY KEY(formules_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE formules_category ADD CONSTRAINT FK_4793CDB9168F3793 FOREIGN KEY (formules_id) REFERENCES formules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formules_category ADD CONSTRAINT FK_4793CDB912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formules_category DROP FOREIGN KEY FK_4793CDB9168F3793');
        $this->addSql('ALTER TABLE formules_category DROP FOREIGN KEY FK_4793CDB912469DE2');
        $this->addSql('DROP TABLE formules_category');
    }
}

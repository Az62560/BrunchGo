<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215073642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formules_product (formules_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_8E41C06168F3793 (formules_id), INDEX IDX_8E41C064584665A (product_id), PRIMARY KEY(formules_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE formules_product ADD CONSTRAINT FK_8E41C06168F3793 FOREIGN KEY (formules_id) REFERENCES formules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formules_product ADD CONSTRAINT FK_8E41C064584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producers ADD illustration VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formules_product DROP FOREIGN KEY FK_8E41C06168F3793');
        $this->addSql('ALTER TABLE formules_product DROP FOREIGN KEY FK_8E41C064584665A');
        $this->addSql('DROP TABLE formules_product');
        $this->addSql('ALTER TABLE producers DROP illustration');
    }
}

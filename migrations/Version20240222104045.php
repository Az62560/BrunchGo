<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222104045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD is_luxe TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE product RENAME INDEX fk_d34a04ad64577843 TO IDX_D34A04AD64577843');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP is_luxe');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_d34a04ad64577843 TO FK_D34A04AD64577843');
    }
}

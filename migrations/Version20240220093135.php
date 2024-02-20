<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220093135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD7738FE2F FOREIGN KEY (order_products_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD7738FE2F ON product (order_products_id)');
        $this->addSql('ALTER TABLE product RENAME INDEX fk_d34a04ad64577843 TO IDX_D34A04AD64577843');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD7738FE2F');
        $this->addSql('DROP INDEX IDX_D34A04AD7738FE2F ON product');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_d34a04ad64577843 TO FK_D34A04AD64577843');
    }
}

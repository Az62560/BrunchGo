<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301105113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE working_day_time_slots (working_day_id INT NOT NULL, time_slots_id INT NOT NULL, INDEX IDX_8400BF8AC2FEC4CB (working_day_id), INDEX IDX_8400BF8ABA14C497 (time_slots_id), PRIMARY KEY(working_day_id, time_slots_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE working_day_time_slots ADD CONSTRAINT FK_8400BF8AC2FEC4CB FOREIGN KEY (working_day_id) REFERENCES working_day (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE working_day_time_slots ADD CONSTRAINT FK_8400BF8ABA14C497 FOREIGN KEY (time_slots_id) REFERENCES time_slots (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE working_day_time_slots DROP FOREIGN KEY FK_8400BF8AC2FEC4CB');
        $this->addSql('ALTER TABLE working_day_time_slots DROP FOREIGN KEY FK_8400BF8ABA14C497');
        $this->addSql('DROP TABLE working_day_time_slots');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241011220244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F0948172CE8');
        $this->addSql('DROP INDEX IDX_52EA1F0948172CE8 ON order_item');
        $this->addSql('ALTER TABLE order_item DROP productss_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item ADD productss_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F0948172CE8 FOREIGN KEY (productss_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F0948172CE8 ON order_item (productss_id)');
    }
}

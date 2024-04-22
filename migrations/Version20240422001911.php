<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422001911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, shop_id INT NOT NULL, product_id INT NOT NULL, client_id INT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, currency VARCHAR(20) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_E1E0B40E4D16C4DD (shop_id), INDEX IDX_E1E0B40E4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) DEFAULT NULL, subcategory VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, country VARCHAR(10) DEFAULT NULL, client_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discount ADD CONSTRAINT FK_E1E0B40E4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE discount ADD CONSTRAINT FK_E1E0B40E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discount DROP FOREIGN KEY FK_E1E0B40E4D16C4DD');
        $this->addSql('ALTER TABLE discount DROP FOREIGN KEY FK_E1E0B40E4584665A');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE shop');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307074512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE main_page ADD advantage1 VARCHAR(255) NOT NULL, ADD advantage2 VARCHAR(255) NOT NULL, ADD advantage3 VARCHAR(255) NOT NULL, DROP advantages');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE main_page ADD advantages LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', DROP advantage1, DROP advantage2, DROP advantage3');
    }
}

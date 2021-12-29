<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228154329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE secret ADD id INT AUTO_INCREMENT NOT NULL, ADD created_at INT NOT NULL, ADD expires_at INT NOT NULL, ADD expires_after_minutes INT NOT NULL, ADD expires_after_views INT NOT NULL, ADD remaining_views INT NOT NULL, DROP createdAt, DROP expiresAt, DROP expiresAfterMinutes, DROP expiresAfterViews, DROP remainingViews, CHANGE secrettext secret_text LONGTEXT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE secret MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE secret DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE secret ADD createdAt INT NOT NULL, ADD expiresAt INT NOT NULL, ADD expiresAfterMinutes INT NOT NULL, ADD expiresAfterViews INT NOT NULL, ADD remainingViews INT NOT NULL, DROP id, DROP created_at, DROP expires_at, DROP expires_after_minutes, DROP expires_after_views, DROP remaining_views, CHANGE secret_text secretText TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE secret ADD PRIMARY KEY (hash)');
    }
}

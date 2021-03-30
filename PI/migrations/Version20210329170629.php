<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329170629 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_conversation (users_id INT NOT NULL, conversation_id INT NOT NULL, INDEX IDX_D9CA00E567B3B43D (users_id), INDEX IDX_D9CA00E59AC0396 (conversation_id), PRIMARY KEY(users_id, conversation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_conversation ADD CONSTRAINT FK_D9CA00E567B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_conversation ADD CONSTRAINT FK_D9CA00E59AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD enabled TINYINT(1) NOT NULL, ADD score INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users_conversation');
        $this->addSql('ALTER TABLE users DROP enabled, DROP score');
    }
}

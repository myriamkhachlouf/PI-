<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331220727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_404021BFA4AEAFEA ON formation (entreprise_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFA4AEAFEA');
        $this->addSql('DROP INDEX IDX_404021BFA4AEAFEA ON formation');
        $this->addSql('ALTER TABLE formation DROP entreprise_id');
    }
}

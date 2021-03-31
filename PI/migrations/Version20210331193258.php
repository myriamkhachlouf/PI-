<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331193258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, main_url LONGTEXT NOT NULL, cover_url LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_users (publication_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_F15DE36538B217A7 (publication_id), INDEX IDX_F15DE36567B3B43D (users_id), PRIMARY KEY(publication_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publication_users ADD CONSTRAINT FK_F15DE36538B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_users ADD CONSTRAINT FK_F15DE36567B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7253258F');
        $this->addSql('ALTER TABLE commentaire ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP datecommentaire, DROP datemodification, CHANGE publication_id publication_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7253258F FOREIGN KEY (postedby_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67797253258F');
        $this->addSql('ALTER TABLE publication ADD image_id INT DEFAULT NULL, ADD updated_at DATETIME NOT NULL, ADD views INT DEFAULT NULL, DROP extension, CHANGE contenu contenu LONGTEXT NOT NULL, CHANGE posted created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67793DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67797253258F FOREIGN KEY (postedby_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF3C67792B36786B ON publication (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF3C67793DA5256D ON publication (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67793DA5256D');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE publication_users');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7253258F');
        $this->addSql('ALTER TABLE commentaire ADD datecommentaire DATE NOT NULL, ADD datemodification DATE NOT NULL, DROP created_at, DROP updated_at, CHANGE publication_id publication_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7253258F FOREIGN KEY (postedby_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67797253258F');
        $this->addSql('DROP INDEX UNIQ_AF3C67792B36786B ON publication');
        $this->addSql('DROP INDEX UNIQ_AF3C67793DA5256D ON publication');
        $this->addSql('ALTER TABLE publication ADD extension BIGINT NOT NULL, ADD posted DATETIME NOT NULL, DROP image_id, DROP created_at, DROP updated_at, DROP views, CHANGE contenu contenu VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67797253258F FOREIGN KEY (postedby_id) REFERENCES entreprise (id)');
    }
}

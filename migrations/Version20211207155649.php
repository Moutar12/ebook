<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211207155649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunte (id INT AUTO_INCREMENT NOT NULL, pret_id INT DEFAULT NULL, adherent_id INT DEFAULT NULL, date_pret DATE NOT NULL, date_retour VARCHAR(255) NOT NULL, INDEX IDX_F75B7D5C1B61704B (pret_id), INDEX IDX_F75B7D5C25F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, genre_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, auteur VARCHAR(255) NOT NULL, annee DATE NOT NULL, dispo TINYINT(1) NOT NULL, INDEX IDX_AC634F994296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, reserve_id INT DEFAULT NULL, adherent_id INT DEFAULT NULL, date_reservation DATE NOT NULL, INDEX IDX_42C849555913AEBF (reserve_id), INDEX IDX_42C8495525F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emprunte ADD CONSTRAINT FK_F75B7D5C1B61704B FOREIGN KEY (pret_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE emprunte ADD CONSTRAINT FK_F75B7D5C25F06C53 FOREIGN KEY (adherent_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F994296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849555913AEBF FOREIGN KEY (reserve_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495525F06C53 FOREIGN KEY (adherent_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F994296D31F');
        $this->addSql('ALTER TABLE emprunte DROP FOREIGN KEY FK_F75B7D5C1B61704B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555913AEBF');
        $this->addSql('DROP TABLE emprunte');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE reservation');
    }
}

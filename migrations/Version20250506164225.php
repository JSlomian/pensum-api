<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506164225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE programs ADD subjects_in_programs_id INT NOT NULL');
        $this->addSql('ALTER TABLE programs ADD CONSTRAINT FK_F1496545CA6F4E09 FOREIGN KEY (subjects_in_programs_id) REFERENCES subjects_in_programs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F1496545CA6F4E09 ON programs (subjects_in_programs_id)');
        $this->addSql('ALTER TABLE subjects_in_programs DROP CONSTRAINT fk_fd52b4e03eb8070a');
        $this->addSql('DROP INDEX uniq_fd52b4e03eb8070a');
        $this->addSql('ALTER TABLE subjects_in_programs DROP program_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subjects_in_programs ADD program_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subjects_in_programs ADD CONSTRAINT fk_fd52b4e03eb8070a FOREIGN KEY (program_id) REFERENCES programs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_fd52b4e03eb8070a ON subjects_in_programs (program_id)');
        $this->addSql('ALTER TABLE programs DROP CONSTRAINT FK_F1496545CA6F4E09');
        $this->addSql('DROP INDEX UNIQ_F1496545CA6F4E09');
        $this->addSql('ALTER TABLE programs DROP subjects_in_programs_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506182233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE programs DROP CONSTRAINT fk_f1496545ca6f4e09');
        $this->addSql('ALTER TABLE subjects DROP CONSTRAINT fk_ab259917ca6f4e09');
        $this->addSql('DROP SEQUENCE subjects_in_programs_id_seq CASCADE');
        $this->addSql('DROP TABLE subjects_in_programs');
        $this->addSql('DROP INDEX uniq_f1496545ca6f4e09');
        $this->addSql('ALTER TABLE programs ADD syllabus_year SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE programs DROP subjects_in_programs_id');
        $this->addSql('DROP INDEX idx_ab259917ca6f4e09');
        $this->addSql('ALTER TABLE subjects RENAME COLUMN subjects_in_programs_id TO program_id');
        $this->addSql('ALTER TABLE subjects ADD CONSTRAINT FK_AB2599173EB8070A FOREIGN KEY (program_id) REFERENCES programs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB2599173EB8070A ON subjects (program_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE subjects_in_programs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE subjects_in_programs (id SERIAL NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE programs ADD subjects_in_programs_id INT NOT NULL');
        $this->addSql('ALTER TABLE programs DROP syllabus_year');
        $this->addSql('ALTER TABLE programs ADD CONSTRAINT fk_f1496545ca6f4e09 FOREIGN KEY (subjects_in_programs_id) REFERENCES subjects_in_programs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_f1496545ca6f4e09 ON programs (subjects_in_programs_id)');
        $this->addSql('ALTER TABLE subjects DROP CONSTRAINT FK_AB2599173EB8070A');
        $this->addSql('DROP INDEX IDX_AB2599173EB8070A');
        $this->addSql('ALTER TABLE subjects RENAME COLUMN program_id TO subjects_in_programs_id');
        $this->addSql('ALTER TABLE subjects ADD CONSTRAINT fk_ab259917ca6f4e09 FOREIGN KEY (subjects_in_programs_id) REFERENCES subjects_in_programs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ab259917ca6f4e09 ON subjects (subjects_in_programs_id)');
    }
}

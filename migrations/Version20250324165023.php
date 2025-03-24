<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324165023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject_lecturers DROP CONSTRAINT fk_c7f470d5ba2d8762');
        $this->addSql('DROP SEQUENCE lecturers_id_seq CASCADE');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, position_id INT DEFAULT NULL, institute_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, pensum SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D93D649DD842E46 ON "user" (position_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649697B0F4C ON "user" (institute_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649DD842E46 FOREIGN KEY (position_id) REFERENCES positions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649697B0F4C FOREIGN KEY (institute_id) REFERENCES institutes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lecturers DROP CONSTRAINT fk_84a5e4ffdd842e46');
        $this->addSql('ALTER TABLE lecturers DROP CONSTRAINT fk_84a5e4ff697b0f4c');
        $this->addSql('DROP TABLE lecturers');
        $this->addSql('DROP INDEX idx_c7f470d5ba2d8762');
        $this->addSql('ALTER TABLE subject_lecturers RENAME COLUMN lecturer_id TO user_id');
        $this->addSql('ALTER TABLE subject_lecturers ADD CONSTRAINT FK_C7F470D5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C7F470D5A76ED395 ON subject_lecturers (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subject_lecturers DROP CONSTRAINT FK_C7F470D5A76ED395');
        $this->addSql('CREATE SEQUENCE lecturers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE lecturers (id SERIAL NOT NULL, position_id INT DEFAULT NULL, institute_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_84a5e4ff697b0f4c ON lecturers (institute_id)');
        $this->addSql('CREATE INDEX idx_84a5e4ffdd842e46 ON lecturers (position_id)');
        $this->addSql('ALTER TABLE lecturers ADD CONSTRAINT fk_84a5e4ffdd842e46 FOREIGN KEY (position_id) REFERENCES positions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lecturers ADD CONSTRAINT fk_84a5e4ff697b0f4c FOREIGN KEY (institute_id) REFERENCES institutes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649DD842E46');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649697B0F4C');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP INDEX IDX_C7F470D5A76ED395');
        $this->addSql('ALTER TABLE subject_lecturers RENAME COLUMN user_id TO lecturer_id');
        $this->addSql('ALTER TABLE subject_lecturers ADD CONSTRAINT fk_c7f470d5ba2d8762 FOREIGN KEY (lecturer_id) REFERENCES lecturers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c7f470d5ba2d8762 ON subject_lecturers (lecturer_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123194253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendance_modes (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, abbreviation VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE class_types (id SERIAL NOT NULL, type VARCHAR(50) NOT NULL, abbreviation VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE education_levels (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, abbreviation VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE institutes (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lecturers (id SERIAL NOT NULL, position_id INT DEFAULT NULL, institute_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_84A5E4FFDD842E46 ON lecturers (position_id)');
        $this->addSql('CREATE INDEX IDX_84A5E4FF697B0F4C ON lecturers (institute_id)');
        $this->addSql('CREATE TABLE majors (id SERIAL NOT NULL, institute_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62EF4352697B0F4C ON majors (institute_id)');
        $this->addSql('CREATE TABLE positions (id SERIAL NOT NULL, description VARCHAR(255) NOT NULL, abbreviation VARCHAR(30) NOT NULL, title VARCHAR(255) NOT NULL, pensum SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE programs (id SERIAL NOT NULL, program_in_majors_id INT NOT NULL, plan_year TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, semester SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F1496545C3A7CB27 ON programs (program_in_majors_id)');
        $this->addSql('CREATE TABLE programs_in_majors (id SERIAL NOT NULL, major_id INT DEFAULT NULL, education_level_id INT DEFAULT NULL, attendance_mode_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_238ABCEE93695C7 ON programs_in_majors (major_id)');
        $this->addSql('CREATE INDEX IDX_238ABCED7A5352E ON programs_in_majors (education_level_id)');
        $this->addSql('CREATE INDEX IDX_238ABCE70226B48 ON programs_in_majors (attendance_mode_id)');
        $this->addSql('CREATE TABLE subject_groups (id SERIAL NOT NULL, subject_id INT NOT NULL, class_type_id INT NOT NULL, amount SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7874F05723EDC87 ON subject_groups (subject_id)');
        $this->addSql('CREATE INDEX IDX_7874F05739EB6F ON subject_groups (class_type_id)');
        $this->addSql('CREATE TABLE subject_hours (id SERIAL NOT NULL, subject_id INT DEFAULT NULL, class_type_id INT NOT NULL, hours_required SMALLINT NOT NULL, syllabus_year TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9B897C5523EDC87 ON subject_hours (subject_id)');
        $this->addSql('CREATE INDEX IDX_9B897C5539EB6F ON subject_hours (class_type_id)');
        $this->addSql('CREATE TABLE subject_lecturers (id SERIAL NOT NULL, subject_id INT NOT NULL, class_type_id INT NOT NULL, lecturer_id INT NOT NULL, subject_hours SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C7F470D523EDC87 ON subject_lecturers (subject_id)');
        $this->addSql('CREATE INDEX IDX_C7F470D539EB6F ON subject_lecturers (class_type_id)');
        $this->addSql('CREATE INDEX IDX_C7F470D5BA2D8762 ON subject_lecturers (lecturer_id)');
        $this->addSql('CREATE TABLE subjects (id SERIAL NOT NULL, subjects_in_programs_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AB259917CA6F4E09 ON subjects (subjects_in_programs_id)');
        $this->addSql('CREATE TABLE subjects_in_programs (id SERIAL NOT NULL, program_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD52B4E03EB8070A ON subjects_in_programs (program_id)');
        $this->addSql('ALTER TABLE lecturers ADD CONSTRAINT FK_84A5E4FFDD842E46 FOREIGN KEY (position_id) REFERENCES positions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lecturers ADD CONSTRAINT FK_84A5E4FF697B0F4C FOREIGN KEY (institute_id) REFERENCES institutes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE majors ADD CONSTRAINT FK_62EF4352697B0F4C FOREIGN KEY (institute_id) REFERENCES institutes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE programs ADD CONSTRAINT FK_F1496545C3A7CB27 FOREIGN KEY (program_in_majors_id) REFERENCES programs_in_majors (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE programs_in_majors ADD CONSTRAINT FK_238ABCEE93695C7 FOREIGN KEY (major_id) REFERENCES majors (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE programs_in_majors ADD CONSTRAINT FK_238ABCED7A5352E FOREIGN KEY (education_level_id) REFERENCES education_levels (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE programs_in_majors ADD CONSTRAINT FK_238ABCE70226B48 FOREIGN KEY (attendance_mode_id) REFERENCES attendance_modes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_groups ADD CONSTRAINT FK_7874F05723EDC87 FOREIGN KEY (subject_id) REFERENCES subjects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_groups ADD CONSTRAINT FK_7874F05739EB6F FOREIGN KEY (class_type_id) REFERENCES class_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_hours ADD CONSTRAINT FK_9B897C5523EDC87 FOREIGN KEY (subject_id) REFERENCES subjects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_hours ADD CONSTRAINT FK_9B897C5539EB6F FOREIGN KEY (class_type_id) REFERENCES class_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_lecturers ADD CONSTRAINT FK_C7F470D523EDC87 FOREIGN KEY (subject_id) REFERENCES subjects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_lecturers ADD CONSTRAINT FK_C7F470D539EB6F FOREIGN KEY (class_type_id) REFERENCES class_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_lecturers ADD CONSTRAINT FK_C7F470D5BA2D8762 FOREIGN KEY (lecturer_id) REFERENCES lecturers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subjects ADD CONSTRAINT FK_AB259917CA6F4E09 FOREIGN KEY (subjects_in_programs_id) REFERENCES subjects_in_programs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subjects_in_programs ADD CONSTRAINT FK_FD52B4E03EB8070A FOREIGN KEY (program_id) REFERENCES programs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lecturers DROP CONSTRAINT FK_84A5E4FFDD842E46');
        $this->addSql('ALTER TABLE lecturers DROP CONSTRAINT FK_84A5E4FF697B0F4C');
        $this->addSql('ALTER TABLE majors DROP CONSTRAINT FK_62EF4352697B0F4C');
        $this->addSql('ALTER TABLE programs DROP CONSTRAINT FK_F1496545C3A7CB27');
        $this->addSql('ALTER TABLE programs_in_majors DROP CONSTRAINT FK_238ABCEE93695C7');
        $this->addSql('ALTER TABLE programs_in_majors DROP CONSTRAINT FK_238ABCED7A5352E');
        $this->addSql('ALTER TABLE programs_in_majors DROP CONSTRAINT FK_238ABCE70226B48');
        $this->addSql('ALTER TABLE subject_groups DROP CONSTRAINT FK_7874F05723EDC87');
        $this->addSql('ALTER TABLE subject_groups DROP CONSTRAINT FK_7874F05739EB6F');
        $this->addSql('ALTER TABLE subject_hours DROP CONSTRAINT FK_9B897C5523EDC87');
        $this->addSql('ALTER TABLE subject_hours DROP CONSTRAINT FK_9B897C5539EB6F');
        $this->addSql('ALTER TABLE subject_lecturers DROP CONSTRAINT FK_C7F470D523EDC87');
        $this->addSql('ALTER TABLE subject_lecturers DROP CONSTRAINT FK_C7F470D539EB6F');
        $this->addSql('ALTER TABLE subject_lecturers DROP CONSTRAINT FK_C7F470D5BA2D8762');
        $this->addSql('ALTER TABLE subjects DROP CONSTRAINT FK_AB259917CA6F4E09');
        $this->addSql('ALTER TABLE subjects_in_programs DROP CONSTRAINT FK_FD52B4E03EB8070A');
        $this->addSql('DROP TABLE attendance_modes');
        $this->addSql('DROP TABLE class_types');
        $this->addSql('DROP TABLE education_levels');
        $this->addSql('DROP TABLE institutes');
        $this->addSql('DROP TABLE lecturers');
        $this->addSql('DROP TABLE majors');
        $this->addSql('DROP TABLE positions');
        $this->addSql('DROP TABLE programs');
        $this->addSql('DROP TABLE programs_in_majors');
        $this->addSql('DROP TABLE subject_groups');
        $this->addSql('DROP TABLE subject_hours');
        $this->addSql('DROP TABLE subject_lecturers');
        $this->addSql('DROP TABLE subjects');
        $this->addSql('DROP TABLE subjects_in_programs');
    }
}

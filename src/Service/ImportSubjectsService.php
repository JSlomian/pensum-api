<?php

namespace App\Service;

use App\Entity\Programs;
use App\Entity\SubjectGroups;
use App\Entity\SubjectHours;
use App\Entity\SubjectLecturers;
use App\Entity\Subjects;
use Doctrine\ORM\EntityManagerInterface;

class ImportSubjectsService
{
    private int $count = 0;

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function copySubjectsToProgram(Programs $program, Programs $programToImport): int
    {
        $this->em->wrapInTransaction(function (EntityManagerInterface $em) use ($program, $programToImport) {
            $this->count = 0;
            foreach ($programToImport->getSubject() as $subject) {
                $copySubject = new Subjects();
                $copySubject->setName($subject->getName())
                    ->setProgram($program);

                $em->persist($copySubject);
                $groups = $subject->getSubjectGroups();
                foreach ($groups as $group) {
                    $g = new SubjectGroups();
                    $g->setAmount($group->getAmount())
                        ->setClassType($group->getClassType());
                    $em->persist($g);
                    $copySubject->addSubjectGroup($g);
                }

                $hours = $subject->getSubjectHours();
                foreach ($hours as $hour) {
                    $h = new SubjectHours();
                    $h->setHoursRequired($hour->getHoursRequired())
                        ->setClassType($hour->getClassType());
                    $em->persist($h);
                    $copySubject->addSubjectHour($h);
                }

                $lecturers = $subject->getSubjectLecturers();
                foreach ($lecturers as $lecturer) {
                    $l = new SubjectLecturers();
                    $l->setClassType($lecturer->getClassType())
                        ->setSubjectHours($lecturer->getSubjectHours())
                        ->setUser($lecturer->getUser());
                    $em->persist($l);
                    $copySubject->addSubjectLecturer($l);
                }
                ++$this->count;
            }
        });

        return $this->count;
    }
}

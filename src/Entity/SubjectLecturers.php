<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubjectLecturersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectLecturersRepository::class)]
#[ApiResource]
class SubjectLecturers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subjectLecturers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subjects $subject = null;

    #[ORM\ManyToOne(inversedBy: 'subjectLecturers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ClassTypes $classType = null;

    #[ORM\ManyToOne(inversedBy: 'subjectLecturers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $subjectHours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?Subjects
    {
        return $this->subject;
    }

    public function setSubject(?Subjects $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getClassType(): ?ClassTypes
    {
        return $this->classType;
    }

    public function setClassType(?ClassTypes $classType): static
    {
        $this->classType = $classType;

        return $this;
    }

    public function getLecturer(): ?User
    {
        return $this->user;
    }

    public function setLecturer(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSubjectHours(): ?int
    {
        return $this->subjectHours;
    }

    public function setSubjectHours(int $subjectHours): static
    {
        $this->subjectHours = $subjectHours;

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubjectHoursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SubjectHoursRepository::class)]
#[ApiResource(
    shortName: "subject_hours",
    normalizationContext: ['groups' => ['subject_hours:read']],
    denormalizationContext: ['groups' => ['subject_hours:write']],
)]
class SubjectHours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('subject_hours:read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subjectHours')]
    #[Groups(['subject_hours:read', 'subject_hours:write'])]
    private ?Subjects $subject = null;

    #[ORM\ManyToOne(inversedBy: 'subjectHours')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['subject_hours:read', 'subject_hours:write'])]
    private ?ClassTypes $classType = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['subject_hours:read', 'subject_hours:write'])]
    private ?int $hoursRequired = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['subject_groups:read', 'subject_groups:write'])]
    private ?\DateTimeInterface $syllabusYear = null;

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

    public function getHoursRequired(): ?int
    {
        return $this->hoursRequired;
    }

    public function setHoursRequired(int $hoursRequired): static
    {
        $this->hoursRequired = $hoursRequired;

        return $this;
    }

    public function getSyllabusYear(): ?\DateTimeInterface
    {
        return $this->syllabusYear;
    }

    public function setSyllabusYear(\DateTimeInterface $syllabusYear): static
    {
        $this->syllabusYear = $syllabusYear;

        return $this;
    }
}

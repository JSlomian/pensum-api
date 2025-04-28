<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProgramsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProgramsRepository::class)]
#[ApiResource(
    shortName: "programs",
    normalizationContext: ['groups' => ['programs:read']],
    denormalizationContext: ['groups' => ['programs:write']],
)]
class Programs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('programs:read')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['programs:read','programs:write'])]
    private ?\DateTimeInterface $planYear = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['programs:read','programs:write'])]
    private ?int $semester = null;

    #[ORM\ManyToOne(inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('programs:read')]
    private ?ProgramsInMajors $programInMajors = null;

    #[ORM\OneToOne(mappedBy: 'program', cascade: ['persist', 'remove'])]
    #[Groups('programs:read')]
    private ?SubjectsInPrograms $subjectsInPrograms = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanYear(): ?\DateTimeInterface
    {
        return $this->planYear;
    }

    public function setPlanYear(\DateTimeInterface $planYear): static
    {
        $this->planYear = $planYear;

        return $this;
    }

    public function getSemester(): ?int
    {
        return $this->semester;
    }

    public function setSemester(int $semester): static
    {
        $this->semester = $semester;

        return $this;
    }

    public function getProgramInMajors(): ?ProgramsInMajors
    {
        return $this->programInMajors;
    }

    public function setProgramInMajors(?ProgramsInMajors $programInMajors): static
    {
        $this->programInMajors = $programInMajors;

        return $this;
    }

    public function getSubjectsInPrograms(): ?SubjectsInPrograms
    {
        return $this->subjectsInPrograms;
    }

    public function setSubjectsInPrograms(?SubjectsInPrograms $subjectsInPrograms): static
    {
        // unset the owning side of the relation if necessary
        if ($subjectsInPrograms === null && $this->subjectsInPrograms !== null) {
            $this->subjectsInPrograms->setProgram(null);
        }

        // set the owning side of the relation if necessary
        if ($subjectsInPrograms !== null && $subjectsInPrograms->getProgram() !== $this) {
            $subjectsInPrograms->setProgram($this);
        }

        $this->subjectsInPrograms = $subjectsInPrograms;

        return $this;
    }
}

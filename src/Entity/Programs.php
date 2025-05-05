<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ProgramsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[ORM\Entity(repositoryClass: ProgramsRepository::class)]
#[Table(
    uniqueConstraints: [
        new UniqueConstraint(
            name: 'uniq_pim_year_semester',
            columns: ['programs_in_majors_id', 'plan_year', 'semester']
        )
    ]
)]
#[Assert\UniqueEntity(
    fields: ['planYear', 'semester', 'programInMajors'],
    message: 'A program with that year, semester and pim already exists.'
)]
#[ApiResource(
    shortName: "programs",
    operations: [
        new Get(
            normalizationContext: ['groups' => ['programs:read',
                'programs_in_majors:read',
                'majors:read',
                'education_levels:read',
                'attendance_modes:read',
                'subjects_in_programs:read'
            ], 'enable_max_depth' => true],
            denormalizationContext: ['groups' => ['programs:write']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => [
                'programs:read',
                'programs_in_majors:read',
                'majors:read',
                'education_levels:read',
                'attendance_modes:read',
                'subjects_in_programs:read'
            ], 'enable_max_depth' => true],
            denormalizationContext: ['groups' => ['programs:write']]
        ),
        new Post(),
        new Put(),
        new Patch(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['programs:read']],
    denormalizationContext: ['groups' => ['programs:write']],
)]
#[ApiFilter(SearchFilter::class, properties: ['programInMajors.id' => 'exact'])]
class Programs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('programs:read')]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: false)]
    #[Groups(['programs:read', 'programs:write'])]
    private ?int $planYear = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: false)]
    #[Groups(['programs:read', 'programs:write'])]
    private ?int $semester = null;

    #[ORM\ManyToOne(inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['programs:read', 'programs:write', 'programs_in_majors:read'])]
    private ?ProgramsInMajors $programInMajors = null;

    #[ORM\OneToOne(mappedBy: 'program', cascade: ['persist', 'remove'])]
    #[MaxDepth(3)]
    #[Groups(['programs:read','programs:write','subjects_in_programs:read'])]
    private ?SubjectsInPrograms $subjectsInPrograms = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanYear(): ?int
    {
        return $this->planYear;
    }

    public function setPlanYear(int $planYear): static
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

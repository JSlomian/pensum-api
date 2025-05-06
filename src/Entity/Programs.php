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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
        new Delete(
            normalizationContext: ['groups' => ['programs:read']],
            denormalizationContext: ['groups' => ['programs:write']],
            extraProperties: ['fetch_partial' => true]
        )
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

    #[ORM\Column(type: Types::SMALLINT, nullable: false)]
    #[Groups(['programs:read', 'programs:write'])]
    private ?int $syllabusYear = null;

    #[ORM\ManyToOne(inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['programs:read', 'programs:write', 'programs_in_majors:read'])]
    #[MaxDepth(1)]
    private ?ProgramsInMajors $programInMajors = null;

    /**
     * @var Collection<int, Subjects>
     */
    #[ORM\OneToMany(
        targetEntity: Subjects::class,
        mappedBy: 'program',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(['programs:read', 'programs:write'])]
    private Collection $subject;

    public function __construct()
    {
        $this->subject = new ArrayCollection();
    }

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

    public function getSyllabusYear(): ?int
    {
        return $this->syllabusYear;
    }

    public function setSyllabusYear(int $syllabusYear): static
    {
        $this->syllabusYear = $syllabusYear;

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

    /**
     * @return Collection<int, Subjects>
     */
    public function getSubject(): Collection
    {
        return $this->subject;
    }

    public function addSubject(Subjects $subject): static
    {
        if (!$this->subject->contains($subject)) {
            $this->subject->add($subject);
            $subject->setProgram($this);
        }

        return $this;
    }

    public function removeSubject(Subjects $subject): static
    {
        if ($this->subject->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getProgram() === $this) {
                $subject->setProgram(null);
            }
        }

        return $this;
    }
}

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
use App\Repository\ProgramsInMajorsRepository;
use App\State\PimDeleteProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[ORM\Entity(repositoryClass: ProgramsInMajorsRepository::class)]
#[Table(
    uniqueConstraints: [
        new UniqueConstraint(
            name: 'uniq_major_edu_att',
            columns: ['major_id', 'education_level_id', 'attendance_mode_id']
        ),
    ]
)]
#[Assert\UniqueEntity(
    fields: ['major', 'educationLevel', 'attendanceMode'],
    message: 'A program with that Major, Education Level & Attendance Mode already exists.'
)]
#[ApiResource(
    shortName: 'programs_in_majors',
    operations: [
        new Get(
            normalizationContext: ['groups' => [
                'programs_in_majors:read',
                'majors:read',
                'attendance_modes:read',
                'education_levels:read',
            ], 'enable_max_depth' => true],
            denormalizationContext: ['groups' => [
                'programs_in_majors:write'],
            ]
        ),
        new GetCollection(
            normalizationContext: ['groups' => [
                'programs_in_majors:read',
                'majors:read',
                'attendance_modes:read',
                'education_levels:read',
            ], 'enable_max_depth' => true],
            denormalizationContext: ['groups' => [
                'programs_in_majors:write'],
            ]
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can create.'
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can update.'
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can modify.'
        ),
        new Delete(
            processor: PimDeleteProcessor::class,
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can delete.'
        ),
    ],
    normalizationContext: ['groups' => ['programs_in_majors:read']],
    denormalizationContext: ['groups' => ['programs_in_majors:write']],
)]
#[ApiFilter(SearchFilter::class, properties: ['major.id' => 'exact'])]
class ProgramsInMajors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('programs_in_majors:read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'programsInMajors')]
    #[MaxDepth(1)]
    #[Groups(['programs_in_majors:read', 'programs_in_majors:write', 'major:read', 'major:write'])]
    private ?Majors $major = null;

    #[ORM\ManyToOne(inversedBy: 'programsInMajors')]
    #[MaxDepth(1)]
    #[Groups(['programs_in_majors:read', 'programs_in_majors:write', 'education_levels:read'])]
    private ?EducationLevels $educationLevel = null;

    #[ORM\ManyToOne(inversedBy: 'programsInMajors')]
    #[MaxDepth(1)]
    #[Groups(['programs_in_majors:read', 'programs_in_majors:write', 'attendance_modes:read'])]
    private ?AttendanceModes $attendanceMode = null;

    /**
     * @var Collection<int, Programs>
     */
    #[ORM\OneToMany(targetEntity: Programs::class, mappedBy: 'programInMajors')]
    #[MaxDepth(1)]
    #[Groups(['programs_in_majors:read', 'programs_in_majors:write'])]
    private Collection $programs;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMajor(): ?Majors
    {
        return $this->major;
    }

    public function setMajor(?Majors $major): static
    {
        $this->major = $major;

        return $this;
    }

    public function getEducationLevel(): ?EducationLevels
    {
        return $this->educationLevel;
    }

    public function setEducationLevel(?EducationLevels $educationLevel): static
    {
        $this->educationLevel = $educationLevel;

        return $this;
    }

    public function getAttendanceMode(): ?AttendanceModes
    {
        return $this->attendanceMode;
    }

    public function setAttendanceMode(?AttendanceModes $attendanceMode): static
    {
        $this->attendanceMode = $attendanceMode;

        return $this;
    }

    /**
     * @return Collection<int, Programs>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Programs $program): static
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
            $program->setProgramInMajors($this);
        }

        return $this;
    }

    public function removeProgram(Programs $program): static
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getProgramInMajors() === $this) {
                $program->setProgramInMajors(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProgramsInMajorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgramsInMajorsRepository::class)]
#[ApiResource]
class ProgramsInMajors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'programsInMajors')]
    private ?Majors $major = null;

    #[ORM\ManyToOne(inversedBy: 'programsInMajors')]
    private ?EducationLevels $educationLevel = null;

    #[ORM\ManyToOne(inversedBy: 'programsInMajors')]
    private ?AttendanceModes $attendanceMode = null;

    /**
     * @var Collection<int, Programs>
     */
    #[ORM\OneToMany(targetEntity: Programs::class, mappedBy: 'programInMajors')]
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

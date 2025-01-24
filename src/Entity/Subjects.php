<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
#[ApiResource]
class Subjects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subject')]
    private ?SubjectsInPrograms $subjectsInPrograms = null;

    /**
     * @var Collection<int, SubjectHours>
     */
    #[ORM\OneToMany(targetEntity: SubjectHours::class, mappedBy: 'subject')]
    private Collection $subjectHours;

    /**
     * @var Collection<int, SubjectGroups>
     */
    #[ORM\OneToMany(targetEntity: SubjectGroups::class, mappedBy: 'subject')]
    private Collection $subjectGroups;

    /**
     * @var Collection<int, SubjectLecturers>
     */
    #[ORM\OneToMany(targetEntity: SubjectLecturers::class, mappedBy: 'subject')]
    private Collection $subjectLecturers;

    public function __construct()
    {
        $this->subjectHours = new ArrayCollection();
        $this->subjectGroups = new ArrayCollection();
        $this->subjectLecturers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubjectsInPrograms(): ?SubjectsInPrograms
    {
        return $this->subjectsInPrograms;
    }

    public function setSubjectsInPrograms(?SubjectsInPrograms $subjectsInPrograms): static
    {
        $this->subjectsInPrograms = $subjectsInPrograms;

        return $this;
    }

    /**
     * @return Collection<int, SubjectHours>
     */
    public function getSubjectHours(): Collection
    {
        return $this->subjectHours;
    }

    public function addSubjectHour(SubjectHours $subjectHour): static
    {
        if (!$this->subjectHours->contains($subjectHour)) {
            $this->subjectHours->add($subjectHour);
            $subjectHour->setSubject($this);
        }

        return $this;
    }

    public function removeSubjectHour(SubjectHours $subjectHour): static
    {
        if ($this->subjectHours->removeElement($subjectHour)) {
            // set the owning side to null (unless already changed)
            if ($subjectHour->getSubject() === $this) {
                $subjectHour->setSubject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SubjectGroups>
     */
    public function getSubjectGroups(): Collection
    {
        return $this->subjectGroups;
    }

    public function addSubjectGroup(SubjectGroups $subjectGroup): static
    {
        if (!$this->subjectGroups->contains($subjectGroup)) {
            $this->subjectGroups->add($subjectGroup);
            $subjectGroup->setSubject($this);
        }

        return $this;
    }

    public function removeSubjectGroup(SubjectGroups $subjectGroup): static
    {
        if ($this->subjectGroups->removeElement($subjectGroup)) {
            // set the owning side to null (unless already changed)
            if ($subjectGroup->getSubject() === $this) {
                $subjectGroup->setSubject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SubjectLecturers>
     */
    public function getSubjectLecturers(): Collection
    {
        return $this->subjectLecturers;
    }

    public function addSubjectLecturer(SubjectLecturers $subjectLecturer): static
    {
        if (!$this->subjectLecturers->contains($subjectLecturer)) {
            $this->subjectLecturers->add($subjectLecturer);
            $subjectLecturer->setSubject($this);
        }

        return $this;
    }

    public function removeSubjectLecturer(SubjectLecturers $subjectLecturer): static
    {
        if ($this->subjectLecturers->removeElement($subjectLecturer)) {
            // set the owning side to null (unless already changed)
            if ($subjectLecturer->getSubject() === $this) {
                $subjectLecturer->setSubject(null);
            }
        }

        return $this;
    }
}

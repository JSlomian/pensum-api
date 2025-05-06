<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
#[ApiResource(
    shortName: "subjects",
    normalizationContext: ['groups' => ['subjects:read']],
    denormalizationContext: ['groups' => ['subjects:write']],
)]
class Subjects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('subjects:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['subjects:read', 'subjects:write'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'subject')]
    #[Groups(['subjects:read', 'subjects:write'])]
    private ?Programs $program = null;

    /**
     * @var Collection<int, SubjectHours>
     */
    #[ORM\OneToMany(
        targetEntity: SubjectHours::class,
        mappedBy: 'subject',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(['subjects:read', 'subjects:write'])]
    private Collection $subjectHours;

    /**
     * @var Collection<int, SubjectGroups>
     */
    #[ORM\OneToMany(
        targetEntity: SubjectGroups::class,
        mappedBy: 'subject',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(['subjects:read', 'subjects:write'])]
    private Collection $subjectGroups;

    /**
     * @var Collection<int, SubjectLecturers>
     */
    #[ORM\OneToMany(
        targetEntity: SubjectLecturers::class,
        mappedBy: 'subject',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(['subjects:read', 'subjects:write'])]
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

//    public function getSubjectsInPrograms(): ?SubjectsInPrograms
//    {
//        return $this->subjectsInPrograms;
//    }
//
//    public function setSubjectsInPrograms(?SubjectsInPrograms $subjectsInPrograms): static
//    {
//        $this->subjectsInPrograms = $subjectsInPrograms;
//
//        return $this;
//    }

    public function getProgram(): ?Programs
    {
        return $this->program;
    }

    public function setProgram(?Programs $program): static
    {
        $this->program = $program;

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

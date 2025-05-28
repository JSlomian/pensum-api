<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[
    ORM\Entity(repositoryClass: SubjectRepository::class)]
#[ApiResource(
    shortName: 'subjects',
    operations: [
        new Get(
            normalizationContext: ['groups' => [
                'subjects:read',
                'subject_groups:read',
                'subject_hours:read',
                'subject_lecturers:read',
            ], 'enable_max_depth' => true],
            denormalizationContext: ['groups' => [
                'subjects:write',
                'subject_groups:write',
                'subject_hours:write',
                'subject_lecturers:write',
            ], 'enable_max_depth' => true],
        ),
        new GetCollection(
            normalizationContext: ['groups' => [
//                'user:read',
                'subjects:read',
                'subject_groups:read',
                'subject_hours:read',
                'subject_lecturers:read',
            ], 'enable_max_depth' => true],
            denormalizationContext: ['groups' => [
                'subjects:write',
                'subject_groups:write',
                'subject_hours:write',
                'subject_lecturers:write',
            ], 'enable_max_depth' => true],
        ),
        new Post(
            denormalizationContext: ['groups' => ['subjects:create']],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can create.'
        ),
        new Put(
            denormalizationContext: ['groups' => ['subjects:write']],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can update.'
        ),
        new Patch(
            normalizationContext: ['groups' => [
                'subjects:read',
                'subject_groups:read',
                'subject_hours:read',
                'subject_lecturers:read',
            ], 'enable_max_depth' => true],
            denormalizationContext: ['groups' => [
                'subjects:write',
                'subject_groups:write',
                'subject_hours:write',
                'subject_lecturers:write',
            ], 'enable_max_depth' => true],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can modify.'
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can delete.'
        ),
    ],
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
    #[Groups(['subjects:read', 'subjects:write', 'subjects:create'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'subject')]
    #[Groups(['subjects:read', 'subjects:write', 'subjects:create'])]
    #[MaxDepth(1)]
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
    #[MaxDepth(1)]
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
    #[MaxDepth(1)]
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
    #[MaxDepth(1)]
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

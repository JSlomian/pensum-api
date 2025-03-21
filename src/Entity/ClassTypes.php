<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClassTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;


#[ORM\Entity(repositoryClass: ClassTypesRepository::class)]
#[ApiResource(
    shortName: "class_types",
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class ClassTypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[NotBlank]
    #[Groups(['read', 'write'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'maxLength' => 50
        ]
    )]
    private ?string $type = null;

    #[ORM\Column(length: 5)]
    #[NotBlank]
    #[Groups(['read', 'write'])]
    private ?string $abbreviation = null;

    /**
     * @var Collection<int, SubjectHours>
     */
    #[Groups('read')]
    #[ORM\OneToMany(targetEntity: SubjectHours::class, mappedBy: 'classType')]
    private Collection $subjectHours;

    /**
     * @var Collection<int, SubjectGroups>
     */
    #[Groups('read')]
    #[ORM\OneToMany(targetEntity: SubjectGroups::class, mappedBy: 'classType')]
    private Collection $subjectGroups;

    /**
     * @var Collection<int, SubjectLecturers>
     */
    #[Groups('read')]
    #[ORM\OneToMany(targetEntity: SubjectLecturers::class, mappedBy: 'classType')]
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;

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
            $subjectHour->setClassType($this);
        }

        return $this;
    }

    public function removeSubjectHour(SubjectHours $subjectHour): static
    {
        if ($this->subjectHours->removeElement($subjectHour)) {
            // set the owning side to null (unless already changed)
            if ($subjectHour->getClassType() === $this) {
                $subjectHour->setClassType(null);
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
            $subjectGroup->setClassType($this);
        }

        return $this;
    }

    public function removeSubjectGroup(SubjectGroups $subjectGroup): static
    {
        if ($this->subjectGroups->removeElement($subjectGroup)) {
            // set the owning side to null (unless already changed)
            if ($subjectGroup->getClassType() === $this) {
                $subjectGroup->setClassType(null);
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
            $subjectLecturer->setClassType($this);
        }

        return $this;
    }

    public function removeSubjectLecturer(SubjectLecturers $subjectLecturer): static
    {
        if ($this->subjectLecturers->removeElement($subjectLecturer)) {
            // set the owning side to null (unless already changed)
            if ($subjectLecturer->getClassType() === $this) {
                $subjectLecturer->setClassType(null);
            }
        }

        return $this;
    }
}

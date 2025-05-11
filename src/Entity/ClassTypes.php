<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ClassTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ClassTypesRepository::class)]
#[ApiResource(
    shortName: "class_types",
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Only admins can create."
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Only admins can update."
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Only admins can modify."
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Only admins can delete."
        ),
    ],
    normalizationContext: ['groups' => ['class_types:read']],
    denormalizationContext: ['groups' => ['class_types:write']],
)]
class ClassTypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('class_types:read')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Groups(['class_types:read', 'class_types:write'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'maxLength' => 50
        ]
    )]
    private ?string $type = null;

    #[ORM\Column(length: 5)]
    #[NotBlank]
    #[Groups(['class_types:read', 'class_types:write'])]
    private ?string $abbreviation = null;

    /**
     * @var Collection<int, SubjectHours>
     */
    #[Groups('class_types:read')]
    #[ORM\OneToMany(targetEntity: SubjectHours::class, mappedBy: 'classType')]
    private Collection $subjectHours;

    /**
     * @var Collection<int, SubjectGroups>
     */
    #[Groups('class_types:read')]
    #[ORM\OneToMany(targetEntity: SubjectGroups::class, mappedBy: 'classType')]
    private Collection $subjectGroups;

    /**
     * @var Collection<int, SubjectLecturers>
     */
    #[Groups('class_types:read')]
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

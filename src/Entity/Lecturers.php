<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LecturersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: LecturersRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
class Lecturers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lecturers')]
    #[Groups(['read', 'write'])]
    private ?Positions $position = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['read', 'write'])]
    private ?int $pensum = null;

    #[ORM\ManyToOne(inversedBy: 'lecturers')]
    #[Groups(['read', 'write'])]
    private ?Institutes $institute = null;

    /**
     * @var Collection<int, SubjectLecturers>
     */
    #[ORM\OneToMany(targetEntity: SubjectLecturers::class, mappedBy: 'lecturer')]
    private Collection $subjectLecturers;

    public function __construct()
    {
        $this->subjectLecturers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?Positions
    {
        return $this->position;
    }

    public function setPosition(?Positions $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getInstitute(): ?Institutes
    {
        return $this->institute;
    }

    public function setInstitute(?Institutes $institute): static
    {
        $this->institute = $institute;

        return $this;
    }

    public function getPensum(): ?int
    {
        return $this->pensum;
    }

    public function setPensum(int $pensum): static
    {
        $this->pensum = $pensum;

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
            $subjectLecturer->setLecturer($this);
        }

        return $this;
    }

    public function removeSubjectLecturer(SubjectLecturers $subjectLecturer): static
    {
        if ($this->subjectLecturers->removeElement($subjectLecturer)) {
            // set the owning side to null (unless already changed)
            if ($subjectLecturer->getLecturer() === $this) {
                $subjectLecturer->setLecturer(null);
            }
        }

        return $this;
    }
}

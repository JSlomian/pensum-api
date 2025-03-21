<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InstitutesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: InstitutesRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
class Institutes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    #[NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    #[NotBlank]
    #[Groups(['read', 'write'])]
    private ?string $abbreviation = null;

    /**
     * @var Collection<int, Majors>
     */
    #[ORM\OneToMany(targetEntity: Majors::class, mappedBy: 'institute')]
    private Collection $majors;

    /**
     * @var Collection<int, Lecturers>
     */
    #[ORM\OneToMany(targetEntity: Lecturers::class, mappedBy: 'institute')]
    private Collection $lecturers;

    public function __construct()
    {
        $this->majors = new ArrayCollection();
        $this->lecturers = new ArrayCollection();
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
     * @return Collection<int, Majors>
     */
    public function getMajors(): Collection
    {
        return $this->majors;
    }

    public function addMajor(Majors $major): static
    {
        if (!$this->majors->contains($major)) {
            $this->majors->add($major);
            $major->setInstitute($this);
        }

        return $this;
    }

    public function removeMajor(Majors $major): static
    {
        if ($this->majors->removeElement($major)) {
            // set the owning side to null (unless already changed)
            if ($major->getInstitute() === $this) {
                $major->setInstitute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lecturers>
     */
    public function getLecturers(): Collection
    {
        return $this->lecturers;
    }

    public function addLecturer(Lecturers $lecturer): static
    {
        if (!$this->lecturers->contains($lecturer)) {
            $this->lecturers->add($lecturer);
            $lecturer->setInstitute($this);
        }

        return $this;
    }

    public function removeLecturer(Lecturers $lecturer): static
    {
        if ($this->lecturers->removeElement($lecturer)) {
            // set the owning side to null (unless already changed)
            if ($lecturer->getInstitute() === $this) {
                $lecturer->setInstitute(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\EducationLevelsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: EducationLevelsRepository::class)]
#[ApiResource(
    shortName: "education_levels",
        operations: [
        new Get(
            normalizationContext: ['groups' => ['education_levels:read', 'education_levels:item:read']],
            denormalizationContext: ['groups' => ['education_levels:write']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['education_levels:read']],
            denormalizationContext: ['groups' => ['education_levels:write']]
        ),
        new Post(),
        new Put(),
        new Patch(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['education_levels:read']],
    denormalizationContext: ['groups' => ['education_levels:write']],
)]
class EducationLevels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('education_levels:read')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['education_levels:read', 'education_levels:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    #[Groups(['education_levels:read', 'education_levels:write'])]
    private ?string $abbreviation = null;

    /**
     * @var Collection<int, ProgramsInMajors>
     */
    #[Groups(['education_levels:item:read'])]
    #[ORM\OneToMany(targetEntity: ProgramsInMajors::class, mappedBy: 'educationLevel')]
    private Collection $programsInMajors;

    public function __construct()
    {
        $this->programsInMajors = new ArrayCollection();
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
     * @return Collection<int, ProgramsInMajors>
     */
    public function getProgramsInMajors(): Collection
    {
        return $this->programsInMajors;
    }

    public function addProgramsInMajor(ProgramsInMajors $programsInMajor): static
    {
        if (!$this->programsInMajors->contains($programsInMajor)) {
            $this->programsInMajors->add($programsInMajor);
            $programsInMajor->setEducationLevel($this);
        }

        return $this;
    }

    public function removeProgramsInMajor(ProgramsInMajors $programsInMajor): static
    {
        if ($this->programsInMajors->removeElement($programsInMajor)) {
            // set the owning side to null (unless already changed)
            if ($programsInMajor->getEducationLevel() === $this) {
                $programsInMajor->setEducationLevel(null);
            }
        }

        return $this;
    }
}

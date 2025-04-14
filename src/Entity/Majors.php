<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MajorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MajorsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['majors:write']],
    denormalizationContext: ['groups' => ['majors:write']]
)]
class Majors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['majors:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['majors:read','majors:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    #[Groups(['majors:read','majors:write'])]
    private ?string $abbreviation = null;

    /**
     * @var Collection<int, ProgramsInMajors>
     */
    #[ORM\OneToMany(targetEntity: ProgramsInMajors::class, mappedBy: 'major')]
    #[Groups(['majors:read'])]
    private Collection $programsInMajors;

    #[ORM\ManyToOne(inversedBy: 'majors')]
    #[Groups(['majors:read'])]
    private ?Institutes $institute = null;

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
            $programsInMajor->setMajor($this);
        }

        return $this;
    }

    public function removeProgramsInMajor(ProgramsInMajors $programsInMajor): static
    {
        if ($this->programsInMajors->removeElement($programsInMajor)) {
            // set the owning side to null (unless already changed)
            if ($programsInMajor->getMajor() === $this) {
                $programsInMajor->setMajor(null);
            }
        }

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
}

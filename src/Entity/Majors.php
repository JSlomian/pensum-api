<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\MajorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MajorsRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['majors:read', 'majors:item:read', 'institutes:read']],
            denormalizationContext: ['groups' => ['majors:write']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['majors:read', 'institutes:read']],
            denormalizationContext: ['groups' => ['majors:write']]
        ),
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
    normalizationContext: ['groups' => ['majors:read']],
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
    #[Groups(['majors:read', 'majors:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    #[Groups(['majors:read', 'majors:write'])]
    private ?string $abbreviation = null;

    /**
     * @var Collection<int, ProgramsInMajors>
     */
    #[ORM\OneToMany(targetEntity: ProgramsInMajors::class, mappedBy: 'major')]
    #[Groups(['majors:item:read'])]
    private Collection $programsInMajors;

    #[ORM\ManyToOne(inversedBy: 'majors')]
    #[Groups(['majors:write', 'majors:read', 'institutes:read'])]
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

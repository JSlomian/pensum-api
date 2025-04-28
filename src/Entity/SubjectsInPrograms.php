<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubjectsInProgramsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SubjectsInProgramsRepository::class)]
#[ApiResource(
    shortName: "subjects_in_programs",
    normalizationContext: ['groups' => ['subjects_in_programs:read']],
    denormalizationContext: ['groups' => ['subjects_in_programs:write']],
)]
class SubjectsInPrograms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('subjects_in_programs:read')]
    private ?int $id = null;

    /**
     * @var Collection<int, Subjects>
     */
    #[ORM\OneToMany(targetEntity: Subjects::class, mappedBy: 'subjectsInPrograms')]
    #[Groups(['subjects_in_programs:read', 'subjects_in_programs:write'])]
    private Collection $subject;

    #[ORM\OneToOne(inversedBy: 'subjectsInPrograms', cascade: ['persist', 'remove'])]
    #[Groups(['subjects_in_programs:read', 'subjects_in_programs:write'])]
    private ?Programs $program = null;

    public function __construct()
    {
        $this->subject = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Subjects>
     */
    public function getSubject(): Collection
    {
        return $this->subject;
    }

    public function addSubject(Subjects $subject): static
    {
        if (!$this->subject->contains($subject)) {
            $this->subject->add($subject);
            $subject->setSubjectsInPrograms($this);
        }

        return $this;
    }

    public function removeSubject(Subjects $subject): static
    {
        if ($this->subject->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getSubjectsInPrograms() === $this) {
                $subject->setSubjectsInPrograms(null);
            }
        }

        return $this;
    }

    public function getProgram(): ?Programs
    {
        return $this->program;
    }

    public function setProgram(?Programs $program): static
    {
        $this->program = $program;

        return $this;
    }
}

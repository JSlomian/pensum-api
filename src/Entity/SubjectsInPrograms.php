<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubjectsInProgramsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectsInProgramsRepository::class)]
#[ApiResource]
class SubjectsInPrograms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Subjects>
     */
    #[ORM\OneToMany(targetEntity: Subjects::class, mappedBy: 'subjectsInPrograms')]
    private Collection $subject;

    #[ORM\OneToOne(inversedBy: 'subjectsInPrograms', cascade: ['persist', 'remove'])]
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

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InstitutesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InstitutesRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['institutes:write']],
    denormalizationContext: ['groups' => ['institutes:write']]
)]
class Institutes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['institutes:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['institutes:read', 'institutes:write'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Groups(['institutes:read', 'institutes:write'])]
    private ?string $abbreviation = null;

    /**
     * @var Collection<int, Majors>
     */
    #[ORM\OneToMany(targetEntity: Majors::class, mappedBy: 'institute')]
    private Collection $majors;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'institute')]
    private Collection $user;

    public function __construct()
    {
        $this->majors = new ArrayCollection();
        $this->user = new ArrayCollection();
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
     * @return Collection<int, User>
     */
    public function getLecturers(): Collection
    {
        return $this->user;
    }

    public function addLecturer(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setInstitute($this);
        }

        return $this;
    }

    public function removeLecturer(User $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getInstitute() === $this) {
                $user->setInstitute(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\PositionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PositionsRepository::class)]
#[ApiResource(
    shortName: "positions",
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
    normalizationContext: ['groups' => ['positions:read']],
    denormalizationContext: ['groups' => ['positions:write']],
)]
class Positions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('positions:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['positions:read', 'positions:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 30)]
    #[Groups(['positions:read', 'positions:write'])]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 255)]
    #[Groups(['positions:read', 'positions:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['positions:read', 'positions:write'])]
    private ?int $pensum = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'position')]
    private Collection $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPensum(): ?int
    {
        return $this->pensum;
    }

    public function setPensum(int|null $pensum): static
    {
        $this->pensum = $pensum;

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
            $user->setPosition($this);
        }

        return $this;
    }

    public function removeLecturer(User $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getPosition() === $this) {
                $user->setPosition(null);
            }
        }

        return $this;
    }
}

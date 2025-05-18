<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['user:read', 'institutes:read', 'user:item:read', 'positions:read']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['user:read', 'institutes:read', 'positions:read']]
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Only admins can create."
        ),
        new Put(
            denormalizationContext: ['groups' => ['user:edit']],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Only admins can update."
        ),
        new Patch(
            denormalizationContext: ['groups' => ['user:edit']],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Only admins can modify."
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Only admins can delete."
        ),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    #[Type('integer')]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:read', 'user:write', 'user:edit'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['user:read', 'user:write', 'user:edit'])]
    #[Type('array')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:write'])]
    #[Type('string')]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    #[Groups(['user:read', 'user:write', 'user:edit'])]
    private ?Positions $position = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write', 'user:edit'])]
    #[Type('string')]
    #[SerializedName('first_name')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write', 'user:edit'])]
    #[Type('string')]
    #[SerializedName('last_name')]
    private ?string $lastName = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    #[Groups(['user:read', 'user:write', 'user:edit'])]
    private ?Institutes $institute = null;

    /**
     * @var Collection<int, SubjectLecturers>
     */
    #[ORM\OneToMany(targetEntity: SubjectLecturers::class, mappedBy: 'user')]
    #[Groups('user:item:read')]
    private Collection $subjectLecturers;

    public function __construct()
    {
        $this->subjectLecturers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

//    public function getPensum(): ?int
//    {
//        return $this->pensum;
//    }
//
//    public function setPensum(int $pensum): static
//    {
//        $this->pensum = $pensum;
//
//        return $this;
//    }

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

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\AttendanceModesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AttendanceModesRepository::class)]
#[ApiResource(
    shortName: "attendance_modes",
    operations: [
        new Get(
            normalizationContext: ['groups' => ['attendance_modes:read', 'attendance_modes:item:read']],
            denormalizationContext: ['groups' => ['attendance_modes:write']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['attendance_modes:read']],
            denormalizationContext: ['groups' => ['attendance_modes:write']]
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
    normalizationContext: ['groups' => ['attendance_modes:read']],
    denormalizationContext: ['groups' => ['attendance_modes:write']],
)]
class AttendanceModes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('attendance_modes:read')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['attendance_modes:read', 'attendance_modes:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    #[Groups(['attendance_modes:read', 'attendance_modes:write'])]
    private ?string $abbreviation = null;

    /**
     * @var Collection<int, ProgramsInMajors>
     */
    #[Groups('attendance_modes:item:read')]
    #[ORM\OneToMany(targetEntity: ProgramsInMajors::class, mappedBy: 'attendanceMode')]
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
            $programsInMajor->setAttendanceMode($this);
        }

        return $this;
    }

    public function removeProgramsInMajor(ProgramsInMajors $programsInMajor): static
    {
        if ($this->programsInMajors->removeElement($programsInMajor)) {
            // set the owning side to null (unless already changed)
            if ($programsInMajor->getAttendanceMode() === $this) {
                $programsInMajor->setAttendanceMode(null);
            }
        }

        return $this;
    }
}

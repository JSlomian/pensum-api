<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubjectGroupsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SubjectGroupsRepository::class)]
#[ApiResource(
    shortName: "subject_groups",
    normalizationContext: ['groups' => ['subject_groups:read']],
    denormalizationContext: ['groups' => ['subject_groups:write']],
)]
class SubjectGroups
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('subject_groups:read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subjectGroups')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['subject_groups:read', 'subject_groups:write','subjects_in_programs:write'])]
    private ?Subjects $subject = null;

    #[ORM\ManyToOne(inversedBy: 'subjectGroups')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['subject_groups:read', 'subject_groups:write'])]
    private ?ClassTypes $classType = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['subject_groups:read', 'subject_groups:write'])]
    private ?int $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?Subjects
    {
        return $this->subject;
    }

    public function setSubject(?Subjects $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getClassType(): ?ClassTypes
    {
        return $this->classType;
    }

    public function setClassType(?ClassTypes $classType): static
    {
        $this->classType = $classType;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}

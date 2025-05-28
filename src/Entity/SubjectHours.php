<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\SubjectHoursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubjectHoursRepository::class)]
#[Table(
    uniqueConstraints: [
        new UniqueConstraint(
            name: 'uniq_sub_ct',
            columns: ['subject_id', 'class_type_id', 'syllabusYear']
        )
    ]
)]
#[Assert\UniqueEntity(
    fields: ['subject', 'classType', 'syllabusYear'],
    message: 'An hour setting for that year, subject and classType is already set'
)]
#[ApiResource(
    shortName: "subject_hours",
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
    normalizationContext: ['groups' => ['subject_hours:read']],
    denormalizationContext: ['groups' => ['subject_hours:write']],
)]
class SubjectHours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['subject_hours:read', 'subject_hours:write'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subjectHours')]
    #[Groups(['subject_hours:read', 'subject_hours:write'])]
    private ?Subjects $subject = null;

    #[ORM\ManyToOne(inversedBy: 'subjectHours')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['subject_hours:read', 'subject_hours:write'])]
    private ?ClassTypes $classType = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['subject_hours:read', 'subject_hours:write'])]
    private ?int $hoursRequired = null;

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

    public function getHoursRequired(): ?int
    {
        return $this->hoursRequired;
    }

    public function setHoursRequired(int $hoursRequired): static
    {
        $this->hoursRequired = $hoursRequired;

        return $this;
    }
}

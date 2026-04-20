<?php

namespace App\Entity;

use App\Repository\ToothTreatmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ToothTreatmentRepository::class)]
#[ORM\Table(name: 'tooth_treatment')]
#[ORM\UniqueConstraint(
    name: 'unique_treatment_per_tooth',
    columns: ['odontogram_id', 'tooth_number', 'treatment_id', 'tooth_face']
)]
class ToothTreatment
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_DONE = 'done';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Odontogram::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Odontogram $odontogram = null;

    #[ORM\ManyToOne(targetEntity: Treatment::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?Treatment $treatment = null;

    #[ORM\Column]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?int $toothNumber = null;

    #[ORM\Column]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private int $toothFace = 0;

    #[ORM\Column(length: 20)]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private string $status = self::STATUS_PENDING;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOdontogram(): ?Odontogram
    {
        return $this->odontogram;
    }

    public function setOdontogram(?Odontogram $odontogram): static
    {
        $this->odontogram = $odontogram;
        return $this;
    }

    public function getTreatment(): ?Treatment
    {
        return $this->treatment;
    }

    public function setTreatment(?Treatment $treatment): static
    {
        $this->treatment = $treatment;
        return $this;
    }

    public function getToothNumber(): ?int
    {
        return $this->toothNumber;
    }

    public function setToothNumber(int $toothNumber): static
    {
        $this->toothNumber = $toothNumber;
        return $this;
    }

    public function getToothFace(): int
    {
        return $this->toothFace;
    }

    public function setToothFace(int $toothFace): static
    {
        $this->toothFace = $toothFace;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }
}

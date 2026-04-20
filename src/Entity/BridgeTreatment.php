<?php

namespace App\Entity;

use App\Repository\BridgeTreatmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BridgeTreatmentRepository::class)]
#[ORM\Table(name: 'bridge_treatment')]
#[ORM\UniqueConstraint(
    name: 'unique_bridge_per_odontogram',
    columns: ['odontogram_id', 'treatment_id', 'start_tooth', 'end_tooth']
)]
class BridgeTreatment
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
    private ?int $startTooth = null;

    #[ORM\Column]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?int $endTooth = null;

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

    public function getStartTooth(): ?int
    {
        return $this->startTooth;
    }

    public function setStartTooth(int $startTooth): static
    {
        $this->startTooth = $startTooth;
        return $this;
    }

    public function getEndTooth(): ?int
    {
        return $this->endTooth;
    }

    public function setEndTooth(int $endTooth): static
    {
        $this->endTooth = $endTooth;
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

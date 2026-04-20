<?php

namespace App\Entity;

use App\Repository\OdontogramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OdontogramRepository::class)]
class Odontogram
{
    public const TYPE_ADULT = 'adult';
    public const TYPE_CHILD = 'child';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['odontogram:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Patient::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Appointment::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?Appointment $appointment = null;

    #[ORM\OneToMany(mappedBy: 'odontogram', targetEntity: ToothPathology::class, cascade: ['persist', 'remove'])]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private Collection $toothPathologies;

    #[ORM\OneToMany(mappedBy: 'odontogram', targetEntity: ToothTreatment::class, cascade: ['persist', 'remove'])]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private Collection $toothTreatments;

    #[ORM\OneToMany(mappedBy: 'odontogram', targetEntity: BridgeTreatment::class, cascade: ['persist', 'remove'])]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private Collection $bridgeTreatments;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: [self::TYPE_ADULT, self::TYPE_CHILD], message: 'Invalid odontogram type.')]
    #[Groups(['odontogram:read'])]
    private string $type = self::TYPE_ADULT;

    public function __construct()
    {
        $this->toothPathologies = new ArrayCollection();
        $this->toothTreatments = new ArrayCollection();
        $this->bridgeTreatments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): static
    {
        $this->appointment = $appointment;

        return $this;
    }

    /**
     * @return Collection<int, ToothPathology>
     */
    public function getToothPathologies(): Collection
    {
        return $this->toothPathologies;
    }

    public function addToothPathology(ToothPathology $toothPathology): static
    {
        if (!$this->toothPathologies->contains($toothPathology)) {
            $this->toothPathologies->add($toothPathology);
            $toothPathology->setOdontogram($this);
        }

        return $this;
    }

    public function removeToothPathology(ToothPathology $toothPathology): static
    {
        if ($this->toothPathologies->removeElement($toothPathology)) {
            if ($toothPathology->getOdontogram() === $this) {
                $toothPathology->setOdontogram(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ToothTreatment>
     */
    public function getToothTreatments(): Collection
    {
        return $this->toothTreatments;
    }

    public function addToothTreatment(ToothTreatment $toothTreatment): static
    {
        if (!$this->toothTreatments->contains($toothTreatment)) {
            $this->toothTreatments->add($toothTreatment);
            $toothTreatment->setOdontogram($this);
        }

        return $this;
    }

    public function removeToothTreatment(ToothTreatment $toothTreatment): static
    {
        if ($this->toothTreatments->removeElement($toothTreatment)) {
            if ($toothTreatment->getOdontogram() === $this) {
                $toothTreatment->setOdontogram(null);
            }
        }

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, BridgeTreatment>
     */
    public function getBridgeTreatments(): Collection
    {
        return $this->bridgeTreatments;
    }

    public function addBridgeTreatment(BridgeTreatment $bridgeTreatment): static
    {
        if (!$this->bridgeTreatments->contains($bridgeTreatment)) {
            $this->bridgeTreatments->add($bridgeTreatment);
            $bridgeTreatment->setOdontogram($this);
        }

        return $this;
    }

    public function removeBridgeTreatment(BridgeTreatment $bridgeTreatment): static
    {
        if ($this->bridgeTreatments->removeElement($bridgeTreatment)) {
            if ($bridgeTreatment->getOdontogram() === $this) {
                $bridgeTreatment->setOdontogram(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\OdontogramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OdontogramRepository::class)]
class Odontogram
{
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

    public function __construct()
    {
        $this->toothPathologies = new ArrayCollection();
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
}

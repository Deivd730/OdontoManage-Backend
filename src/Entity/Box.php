<?php

namespace App\Entity;

use App\Repository\BoxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoxRepository::class)]
class Box
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'box', targetEntity: Dentist::class)]
    private Collection $dentists;

    #[ORM\OneToMany(mappedBy: 'box', targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        $this->dentists = new ArrayCollection();
        $this->appointments = new ArrayCollection();
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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Dentist>
     */
    public function getDentists(): Collection
    {
        return $this->dentists;
    }

    public function addDentist(Dentist $dentist): static
    {
        if (!$this->dentists->contains($dentist)) {
            $this->dentists->add($dentist);
            $dentist->setBox($this);
        }

        return $this;
    }

    public function removeDentist(Dentist $dentist): static
    {
        if ($this->dentists->removeElement($dentist)) {
            if ($dentist->getBox() === $this) {
                $dentist->setBox(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setBox($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            if ($appointment->getBox() === $this) {
                $appointment->setBox(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use App\Validator\DentistAvailableDay;
use App\Validator\UniqueBoxTimeSlot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
#[UniqueBoxTimeSlot]
#[DentistAvailableDay]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['appointment:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Patient::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'appointment:write'])]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Dentist::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'appointment:write'])]
    private ?Dentist $dentist = null;

    #[ORM\ManyToOne(targetEntity: Box::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'appointment:write'])]
    private ?Box $box = null;

    #[ORM\ManyToOne(targetEntity: Treatment::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'appointment:write'])]
    private ?Treatment $treatment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['appointment:read', 'appointment:write'])]
    private ?\DateTimeInterface $visitDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['appointment:read', 'appointment:write'])]
    private ?string $consultationReason = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'relatedAppointments')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $parentAppointment = null;

    #[ORM\OneToMany(mappedBy: 'parentAppointment', targetEntity: self::class)]
    private Collection $relatedAppointments;

    #[ORM\OneToMany(mappedBy: 'appointment', targetEntity: Odontogram::class)]
    private Collection $odontograms;

    public function __construct()
    {
        $this->relatedAppointments = new ArrayCollection();
        $this->odontograms = new ArrayCollection();
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

    public function getDentist(): ?Dentist
    {
        return $this->dentist;
    }

    public function setDentist(?Dentist $dentist): static
    {
        $this->dentist = $dentist;

        return $this;
    }

    public function getBox(): ?Box
    {
        return $this->box;
    }

    public function setBox(?Box $box): static
    {
        $this->box = $box;

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

    public function getVisitDate(): ?\DateTimeInterface
    {
        return $this->visitDate;
    }

    public function setVisitDate(\DateTimeInterface $visitDate): static
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    public function getConsultationReason(): ?string
    {
        return $this->consultationReason;
    }

    public function setConsultationReason(?string $consultationReason): static
    {
        $this->consultationReason = $consultationReason;

        return $this;
    }

    public function getParentAppointment(): ?self
    {
        return $this->parentAppointment;
    }

    public function setParentAppointment(?self $parentAppointment): static
    {
        $this->parentAppointment = $parentAppointment;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRelatedAppointments(): Collection
    {
        return $this->relatedAppointments;
    }

    public function addRelatedAppointment(self $appointment): static
    {
        if (!$this->relatedAppointments->contains($appointment)) {
            $this->relatedAppointments->add($appointment);
            $appointment->setParentAppointment($this);
        }

        return $this;
    }

    public function removeRelatedAppointment(self $appointment): static
    {
        if ($this->relatedAppointments->removeElement($appointment)) {
            if ($appointment->getParentAppointment() === $this) {
                $appointment->setParentAppointment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Odontogram>
     */
    public function getOdontograms(): Collection
    {
        return $this->odontograms;
    }

    public function addOdontogram(Odontogram $odontogram): static
    {
        if (!$this->odontograms->contains($odontogram)) {
            $this->odontograms->add($odontogram);
            $odontogram->setAppointment($this);
        }

        return $this;
    }

    public function removeOdontogram(Odontogram $odontogram): static
    {
        if ($this->odontograms->removeElement($odontogram)) {
            if ($odontogram->getAppointment() === $this) {
                $odontogram->setAppointment(null);
            }
        }

        return $this;
    }
}

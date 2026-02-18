<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Patient::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Dentist::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dentist $dentist = null;

    #[ORM\ManyToOne(targetEntity: Box::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Box $box = null;

    #[ORM\ManyToOne(targetEntity: Treatment::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Treatment $treatment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $visitDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $consultationReason = null;

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
}

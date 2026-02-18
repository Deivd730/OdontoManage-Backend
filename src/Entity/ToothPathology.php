<?php

namespace App\Entity;

use App\Repository\ToothPathologyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ToothPathologyRepository::class)]
class ToothPathology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Patient::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Tooth::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tooth $tooth = null;

    #[ORM\ManyToOne(targetEntity: Pathology::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pathology $pathology = null;

    #[ORM\Column]
    private ?int $toothFace = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

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

    public function getTooth(): ?Tooth
    {
        return $this->tooth;
    }

    public function setTooth(?Tooth $tooth): static
    {
        $this->tooth = $tooth;

        return $this;
    }

    public function getPathology(): ?Pathology
    {
        return $this->pathology;
    }

    public function setPathology(?Pathology $pathology): static
    {
        $this->pathology = $pathology;

        return $this;
    }

    public function getToothFace(): ?int
    {
        return $this->toothFace;
    }

    public function setToothFace(int $toothFace): static
    {
        $this->toothFace = $toothFace;

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
}

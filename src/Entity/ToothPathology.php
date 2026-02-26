<?php

namespace App\Entity;

use App\Repository\ToothPathologyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ToothPathologyRepository::class)]
class ToothPathology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['odontogram:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Odontogram::class, inversedBy: 'toothPathologies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Odontogram $odontogram = null;

    #[ORM\ManyToOne(targetEntity: Tooth::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?Tooth $tooth = null;

    #[ORM\ManyToOne(targetEntity: Pathology::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?Pathology $pathology = null;

    #[ORM\Column]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?int $toothFace = null;

    #[ORM\Column(length: 255)]
    #[Groups(['odontogram:read', 'odontogram:write'])]
    private ?string $status = null;

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

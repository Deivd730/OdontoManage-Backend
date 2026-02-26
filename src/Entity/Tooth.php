<?php

namespace App\Entity;

use App\Repository\ToothRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ToothRepository::class)]
class Tooth
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tooth:read', 'odontogram:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['tooth:read', 'tooth:write', 'odontogram:read', 'odontogram:write'])]
    private ?int $toothNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['tooth:read', 'tooth:write', 'odontogram:read'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'tooth', targetEntity: ToothPathology::class)]
    private Collection $toothPathologies;

    public function __construct()
    {
        $this->toothPathologies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
            $toothPathology->setTooth($this);
        }

        return $this;
    }

    public function removeToothPathology(ToothPathology $toothPathology): static
    {
        if ($this->toothPathologies->removeElement($toothPathology)) {
            if ($toothPathology->getTooth() === $this) {
                $toothPathology->setTooth(null);
            }
        }

        return $this;
    }
}

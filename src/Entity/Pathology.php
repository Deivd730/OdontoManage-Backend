<?php

namespace App\Entity;

use App\Repository\PathologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PathologyRepository::class)]
class Pathology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pathology:read', 'odontogram:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['pathology:read', 'pathology:write', 'odontogram:read', 'odontogram:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['pathology:read', 'pathology:write'])]
    private ?\DateTimeInterface $time = null;

    #[ORM\OneToMany(mappedBy: 'pathology', targetEntity: ToothPathology::class)]
    private Collection $toothPathologies;

    public function __construct()
    {
        $this->toothPathologies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): static
    {
        $this->time = $time;

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
            $toothPathology->setPathology($this);
        }

        return $this;
    }

    public function removeToothPathology(ToothPathology $toothPathology): static
    {
        if ($this->toothPathologies->removeElement($toothPathology)) {
            if ($toothPathology->getPathology() === $this) {
                $toothPathology->setPathology(null);
            }
        }

        return $this;
    }
}

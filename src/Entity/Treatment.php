<?php

namespace App\Entity;

use App\Repository\TreatmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TreatmentRepository::class)]
class Treatment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['treatment:read', 'appointment:read', 'dentist:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['treatment:read', 'treatment:write', 'appointment:read', 'odontogram:read', 'dentist:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['treatment:read', 'treatment:write', 'appointment:read', 'dentist:read'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['treatment:read', 'treatment:write', 'appointment:read', 'dentist:read'])]
    private ?int $durationMinutes = null;

    /**
     * @var Collection<int, Dentist>
     */
    #[ORM\ManyToMany(targetEntity: Dentist::class, mappedBy: 'treatments')]
    private Collection $dentists;

    public function __construct()
    {
        $this->dentists = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDurationMinutes(): ?int
    {
        return $this->durationMinutes;
    }

    public function setDurationMinutes(int $durationMinutes): static
    {
        $this->durationMinutes = $durationMinutes;

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
        }

        return $this;
    }

    public function removeDentist(Dentist $dentist): static
    {
        $this->dentists->removeElement($dentist);

        return $this;
    }
}

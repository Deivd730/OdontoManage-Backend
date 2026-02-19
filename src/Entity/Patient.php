<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PatientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['patient:read']],
    denormalizationContext: ['groups' => ['patient:write']]
)]
#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[ORM\Table(
    uniqueConstraints: [
        new ORM\UniqueConstraint(name: "unique_national_id", columns: ["national_id"])
    ]
)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['patient:read'])]
    private ?int $id = null;

    #[Assert\NotBlank(message: "First name is required.")]
    #[Assert\Length(min: 2, max: 100)]
    #[ORM\Column(length: 100)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $firstName = null;

    #[Assert\NotBlank(message: "Last name is required.")]
    #[Assert\Length(min: 2, max: 100)]
    #[ORM\Column(length: 100)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $lastName = null;

    #[Assert\NotBlank(message: "National ID is required.")]
    #[Assert\Length(min: 5, max: 20)]
    #[ORM\Column(length: 20, nullable: false)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $nationalId = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $socialSecurityNumber = null;

    #[Assert\Length(max: 20)]
    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $phone = null;

    #[Assert\Email(message: "Invalid email format.")]
    #[Assert\Length(max: 150)]
    #[ORM\Column(length: 150, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $address = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $billingData = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $healthStatus = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $familyHistory = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $lifestyleHabits = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $medicationAllergies = null;

    #[Assert\NotBlank(message: "Registration date is required.")]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?\DateTimeImmutable $registrationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $image = null;

    public function __construct()
    {
        $this->registrationDate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getNationalId(): ?string
    {
        return $this->nationalId;
    }
    public function setNationalId(?string $nationalId): static
    {
        $this->nationalId = $nationalId;
        return $this;
    }

    public function getSocialSecurityNumber(): ?string
    {
        return $this->socialSecurityNumber;
    }
    public function setSocialSecurityNumber(?string $value): static
    {
        $this->socialSecurityNumber = $value;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function setAddress(?string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getBillingData(): ?string
    {
        return $this->billingData;
    }
    public function setBillingData(?string $billingData): static
    {
        $this->billingData = $billingData;
        return $this;
    }

    public function getHealthStatus(): ?string
    {
        return $this->healthStatus;
    }
    public function setHealthStatus(?string $healthStatus): static
    {
        $this->healthStatus = $healthStatus;
        return $this;
    }

    public function getFamilyHistory(): ?string
    {
        return $this->familyHistory;
    }
    public function setFamilyHistory(?string $familyHistory): static
    {
        $this->familyHistory = $familyHistory;
        return $this;
    }

    public function getLifestyleHabits(): ?string
    {
        return $this->lifestyleHabits;
    }
    public function setLifestyleHabits(?string $lifestyleHabits): static
    {
        $this->lifestyleHabits = $lifestyleHabits;
        return $this;
    }

    public function getMedicationAllergies(): ?string
    {
        return $this->medicationAllergies;
    }
    public function setMedicationAllergies(?string $value): static
    {
        $this->medicationAllergies = $value;
        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeImmutable
    {
        return $this->registrationDate;
    }
    public function setRegistrationDate(\DateTimeImmutable $date): static
    {
        $this->registrationDate = $date;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }
}

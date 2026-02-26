<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
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

    #[Vich\UploadableField(mapping: 'profile_images', fileNameProperty: 'profileImageName')]
    #[Groups(['patient:write'])]
    private ?File $profileImageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['patient:read'])]
    private ?string $profileImageName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Dentist::class, inversedBy: 'patients')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Dentist $dentist = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Document::class)]
    private Collection $documents;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Odontogram::class)]
    private Collection $odontograms;

    public function __construct()
    {
        $this->registrationDate = new \DateTimeImmutable();
        $this->documents = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->odontograms = new ArrayCollection();
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

    public function setProfileImageFile(?File $profileImageFile = null): void
    {
        $this->profileImageFile = $profileImageFile;

        if (null !== $profileImageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getProfileImageFile(): ?File
    {
        return $this->profileImageFile;
    }

    public function setProfileImageName(?string $profileImageName): void
    {
        $this->profileImageName = $profileImageName;
    }

    public function getProfileImageName(): ?string
    {
        return $this->profileImageName;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setPatient($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            if ($document->getPatient() === $this) {
                $document->setPatient(null);
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
            $appointment->setPatient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            if ($appointment->getPatient() === $this) {
                $appointment->setPatient(null);
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
            $odontogram->setPatient($this);
        }

        return $this;
    }

    public function removeOdontogram(Odontogram $odontogram): static
    {
        if ($this->odontograms->removeElement($odontogram)) {
            if ($odontogram->getPatient() === $this) {
                $odontogram->setPatient(null);
            }
        }

        return $this;
    }
}

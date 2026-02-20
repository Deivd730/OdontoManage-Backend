<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\DentistRepository;
use App\State\DentistPasswordHasher;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DentistRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(processor: DentistPasswordHasher::class),
        new Get(),
        new Put(processor: DentistPasswordHasher::class),
        new Patch(processor: DentistPasswordHasher::class),
        new Delete()
    ],
    normalizationContext: ['groups' => ['dentist:read']],
    denormalizationContext: ['groups' => ['dentist:write']]
)]
class Dentist implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['dentist:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['dentist:read', 'dentist:write'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['dentist:read', 'dentist:write'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['dentist:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['dentist:read', 'dentist:write'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['dentist:read', 'dentist:write'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['dentist:read', 'dentist:write'])]
    private ?string $specialty = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['dentist:read', 'dentist:write'])]
    private ?string $availableDays = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['dentist:read', 'dentist:write'])]
    private ?string $phone = null;

    #[Vich\UploadableField(mapping: 'profile_images', fileNameProperty: 'profileImageName')]
    #[Groups(['dentist:write'])]
    private ?File $profileImageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['dentist:read'])]
    private ?string $profileImageName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }



    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(?string $specialty): static
    {
        $this->specialty = $specialty;

        return $this;
    }

    public function getAvailableDays(): ?string
    {
        return $this->availableDays;
    }

    public function setAvailableDays(?string $availableDays): static
    {
        $this->availableDays = $availableDays;

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
}

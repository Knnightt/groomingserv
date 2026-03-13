<?php

namespace App\Entity;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\PetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PetRepository::class)]
class Pet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Choice(['Dog', 'Cat', 'Bird', 'Rabbit', 'Hamster', 'Guinea Pig', 'Other'])]
    private ?string $species = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $breed = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 0, max: 50)]
    private ?int $age = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Choice(['Male', 'Female', 'Unknown'])]
    private ?string $gender = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $medicalNotes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\Range(min: 0.1, max: 100)]
    private ?float $weight = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Choice(['Puppy', 'Adult', 'Senior'])]
    private ?string $lifeStage = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isNeutered = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isVaccinated = false;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $coatType = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $temperament = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $allergies = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'pet', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    // In the __construct() method, initialize it:
    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->isActive = true; // Default to active
    }



   


    // Add getter and setter
    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
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

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;
        return $this;
    }

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(?string $breed): static
    {
        $this->breed = $breed;
        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;
        return $this;
    }

    public function getMedicalNotes(): ?string
    {
        return $this->medicalNotes;
    }

    public function setMedicalNotes(?string $medicalNotes): static
    {
        $this->medicalNotes = $medicalNotes;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;
        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): static
    {
        $this->weight = $weight;
        return $this;
    }

    public function getLifeStage(): ?string
    {
        return $this->lifeStage;
    }

    public function setLifeStage(?string $lifeStage): static
    {
        $this->lifeStage = $lifeStage;
        return $this;
    }

    public function isNeutered(): bool
    {
        return $this->isNeutered;
    }

    public function setIsNeutered(bool $isNeutered): static
    {
        $this->isNeutered = $isNeutered;
        return $this;
    }

    public function isVaccinated(): bool
    {
        return $this->isVaccinated;
    }

    public function setIsVaccinated(bool $isVaccinated): static
    {
        $this->isVaccinated = $isVaccinated;
        return $this;
    }

    public function getCoatType(): ?string
    {
        return $this->coatType;
    }

    public function setCoatType(?string $coatType): static
    {
        $this->coatType = $coatType;
        return $this;
    }

    public function getTemperament(): ?string
    {
        return $this->temperament;
    }

    public function setTemperament(?string $temperament): static
    {
        $this->temperament = $temperament;
        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): static
    {
        $this->allergies = $allergies;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;
        return $this;
    }

    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setPet($this);
        }
        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            if ($appointment->getPet() === $this) {
                $appointment->setPet(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? 'Unnamed Pet';
    }
}
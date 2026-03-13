<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $price = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $duration = 60;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Choice(['Basic', 'Premium', 'Ultimate', 'Special', 'Medical'])]
    private ?string $category = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $features = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: 'boolean')]
    private bool $requiresSpecialEquipment = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $specialInstructions = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $minPetAge = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $maxPetAge = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $weightLimit = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getFeatures(): ?string
    {
        return $this->features;
    }

    public function setFeatures(?string $features): static
    {
        $this->features = $features;
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

    public function isRequiresSpecialEquipment(): bool
    {
        return $this->requiresSpecialEquipment;
    }

    public function setRequiresSpecialEquipment(bool $requiresSpecialEquipment): static
    {
        $this->requiresSpecialEquipment = $requiresSpecialEquipment;
        return $this;
    }

    public function getSpecialInstructions(): ?string
    {
        return $this->specialInstructions;
    }

    public function setSpecialInstructions(?string $specialInstructions): static
    {
        $this->specialInstructions = $specialInstructions;
        return $this;
    }

    public function getMinPetAge(): ?int
    {
        return $this->minPetAge;
    }

    public function setMinPetAge(?int $minPetAge): static
    {
        $this->minPetAge = $minPetAge;
        return $this;
    }

    public function getMaxPetAge(): ?int
    {
        return $this->maxPetAge;
    }

    public function setMaxPetAge(?int $maxPetAge): static
    {
        $this->maxPetAge = $maxPetAge;
        return $this;
    }

    public function getWeightLimit(): ?float
    {
        return $this->weightLimit;
    }

    public function setWeightLimit(?float $weightLimit): static
    {
        $this->weightLimit = $weightLimit;
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

    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setService($this);
        }
        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            if ($appointment->getService() === $this) {
                $appointment->setService(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? 'Unnamed Service';
    }
}
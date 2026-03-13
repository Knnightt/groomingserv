<?php

namespace App\Entity;

use App\Repository\StaffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StaffRepository::class)]
class Staff
{
    const ROLE_GROOMER = 'ROLE_GROOMER';
    const ROLE_RECEPTIONIST = 'ROLE_RECEPTIONIST';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_VETERINARIAN = 'ROLE_VETERINARIAN';
    
    const STATUS_ACTIVE = 'Active';
    const STATUS_ON_LEAVE = 'On Leave';
    const STATUS_INACTIVE = 'Inactive';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'staff', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $staffId = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Choice([
        self::ROLE_GROOMER,
        self::ROLE_RECEPTIONIST,
        self::ROLE_MANAGER,
        self::ROLE_VETERINARIAN
    ])]
    private ?string $staffRole = self::ROLE_GROOMER;

    #[ORM\Column(type: 'json')]
    private array $specializations = [];

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $biography = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $experienceYears = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?string $hourlyRate = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $hireDate = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $terminationDate = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice([self::STATUS_ACTIVE, self::STATUS_ON_LEAVE, self::STATUS_INACTIVE])]
    private ?string $employmentStatus = self::STATUS_ACTIVE;

    #[ORM\Column(type: 'boolean')]
    private bool $canHandleAggressivePets = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isCertified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $certifications = null;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $workingDays = [];

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'assignedStaff', targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->employmentStatus = self::STATUS_ACTIVE;
        $this->hireDate = new \DateTimeImmutable();
        $this->specializations = [];
        $this->workingDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getStaffId(): ?string
    {
        return $this->staffId;
    }

    public function setStaffId(string $staffId): static
    {
        $this->staffId = $staffId;
        return $this;
    }

    public function getStaffRole(): ?string
    {
        return $this->staffRole;
    }

    public function setStaffRole(string $staffRole): static
    {
        $this->staffRole = $staffRole;
        return $this;
    }

    public function getSpecializations(): array
    {
        return $this->specializations;
    }

    public function setSpecializations(array $specializations): static
    {
        $this->specializations = $specializations;
        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;
        return $this;
    }

    public function getExperienceYears(): ?int
    {
        return $this->experienceYears;
    }

    public function setExperienceYears(?int $experienceYears): static
    {
        $this->experienceYears = $experienceYears;
        return $this;
    }

    public function getHourlyRate(): ?float
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(float $hourlyRate): static
    {
        $this->hourlyRate = $hourlyRate;
        return $this;
    }

    public function getHireDate(): ?\DateTimeImmutable
    {
        return $this->hireDate;
    }

    public function setHireDate(\DateTimeImmutable $hireDate): static
    {
        $this->hireDate = $hireDate;
        return $this;
    }

    public function getTerminationDate(): ?\DateTimeImmutable
    {
        return $this->terminationDate;
    }

    public function setTerminationDate(?\DateTimeImmutable $terminationDate): static
    {
        $this->terminationDate = $terminationDate;
        return $this;
    }

    public function getEmploymentStatus(): ?string
    {
        return $this->employmentStatus;
    }

    public function setEmploymentStatus(string $employmentStatus): static
    {
        $this->employmentStatus = $employmentStatus;
        return $this;
    }

    public function isCanHandleAggressivePets(): bool
    {
        return $this->canHandleAggressivePets;
    }

    public function setCanHandleAggressivePets(bool $canHandleAggressivePets): static
    {
        $this->canHandleAggressivePets = $canHandleAggressivePets;
        return $this;
    }

    public function isCertified(): bool
    {
        return $this->isCertified;
    }

    public function setIsCertified(bool $isCertified): static
    {
        $this->isCertified = $isCertified;
        return $this;
    }

    public function getCertifications(): ?string
    {
        return $this->certifications;
    }

    public function setCertifications(?string $certifications): static
    {
        $this->certifications = $certifications;
        return $this;
    }

    public function getWorkingDays(): array
    {
        return $this->workingDays;
    }

    public function setWorkingDays(?array $workingDays): static
    {
        $this->workingDays = $workingDays ?? [];
        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;
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
            $appointment->setAssignedStaff($this);
        }
        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            if ($appointment->getAssignedStaff() === $this) {
                $appointment->setAssignedStaff(null);
            }
        }
        return $this;
    }

    public function getDisplayName(): string
    {
        if ($this->user && $this->user->getUserProfile()) {
            $name = $this->user->getUserProfile()->getFullName();
            if ($name) {
                return $name;
            }
        }
        return 'Staff #' . $this->id;
    }

    public function getRoleLabel(): string
    {
        $labels = [
            self::ROLE_GROOMER => 'Groomer',
            self::ROLE_RECEPTIONIST => 'Receptionist',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_VETERINARIAN => 'Veterinarian'
        ];
        return $labels[$this->staffRole] ?? 'Unknown';
    }

    public function getWorkingHours(): string
    {
        if ($this->startTime && $this->endTime) {
            return $this->startTime->format('g:i A') . ' - ' . $this->endTime->format('g:i A');
        }
        return 'Not set';
    }

    public function isActive(): bool
    {
        return $this->employmentStatus === self::STATUS_ACTIVE;
    }

    public function __toString(): string
    {
        return 'Staff #' . $this->id;
    }
}
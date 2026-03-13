<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    const STATUS_PENDING = 'Pending';
    const STATUS_CONFIRMED = 'Confirmed';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_NO_SHOW = 'No Show';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\ManyToOne(targetEntity: Pet::class, inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Pet $pet = null;

    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Service $service = null;

    #[ORM\ManyToOne(targetEntity: Staff::class, inversedBy: 'appointments')]
    private ?Staff $assignedStaff = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank]
    #[Assert\GreaterThan('today')]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank]
    #[Assert\Expression(
        "this.getStartAt() < this.getEndAt()",
        message: "End time must be after start time"
    )]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice([
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
        self::STATUS_NO_SHOW
    ])]
    private ?string $status = self::STATUS_PENDING;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $amount = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $groomerNotes = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $discount = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $tax = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isPaid = false;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $paymentDate = null;

    #[ORM\Column(type: 'boolean')]
    private bool $reminderSent = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->status = self::STATUS_PENDING;
        $this->isPaid = false;
        $this->reminderSent = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;
        return $this;
    }

    public function getPet(): ?Pet
    {
        return $this->pet;
    }

    public function setPet(?Pet $pet): static
    {
        $this->pet = $pet;
        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;
        return $this;
    }

    public function getAssignedStaff(): ?Staff
    {
        return $this->assignedStaff;
    }

    public function setAssignedStaff(?Staff $assignedStaff): static
    {
        $this->assignedStaff = $assignedStaff;
        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;
        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;
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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getGroomerNotes(): ?string
    {
        return $this->groomerNotes;
    }

    public function setGroomerNotes(?string $groomerNotes): static
    {
        $this->groomerNotes = $groomerNotes;
        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): static
    {
        $this->discount = $discount;
        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(?float $tax): static
    {
        $this->tax = $tax;
        return $this;
    }

    public function getTotalAmount(): ?float
    {
        $total = $this->amount;
        if ($this->discount) {
            $total -= $this->discount;
        }
        if ($this->tax) {
            $total += $this->tax;
        }
        return max(0, $total);
    }

    public function isPaid(): bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;
        if ($isPaid) {
            $this->paymentDate = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getPaymentDate(): ?\DateTimeImmutable
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?\DateTimeImmutable $paymentDate): static
    {
        $this->paymentDate = $paymentDate;
        return $this;
    }

    public function isReminderSent(): bool
    {
        return $this->reminderSent;
    }

    public function setReminderSent(bool $reminderSent): static
    {
        $this->reminderSent = $reminderSent;
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

    public function getDuration(): int
    {
        if ($this->startAt && $this->endAt) {
            $interval = $this->startAt->diff($this->endAt);
            return ($interval->h * 60) + $interval->i;
        }
        return $this->service ? $this->service->getDuration() : 60;
    }

    public function __toString(): string
    {
        return sprintf('Appointment #%d - %s', $this->id, $this->pet ? $this->pet->getName() : 'Unknown');
    }
}
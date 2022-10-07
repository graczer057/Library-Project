<?php

namespace App\Entity\Books;

use App\Repository\Books\RentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentsRepository::class)]
class Rents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Reservations $reservationId = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expireDate = null;

    #[ORM\Column]
    private ?bool $isReturned = null;

    public function __construct(
        Reservations $reservations
    ) {
        $this->reservationId = $reservations;
        $this->isActive = true;
        $date = new \DateTime("now");
        $this->expireDate = $date->modify("+ 7 days");
        $this->isReturned = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationId(): ?Reservations
    {
        return $this->reservationId;
    }

    public function setReservationId(?Reservations $reservationId): self
    {
        $this->reservationId = $reservationId;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getExpireDate(): ?\DateTimeInterface
    {
        return $this->expireDate;
    }

    public function setExpireDate(\DateTimeInterface $expireDate): self
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    public function getIsReturned(): ?bool
    {
        return $this->isReturned;
    }

    public function setIsReturned(bool $isReturned): self
    {
        $this->isReturned = $isReturned;

        return $this;
    }
}

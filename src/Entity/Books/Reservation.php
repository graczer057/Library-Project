<?php

namespace App\Entity\Books;

use App\Entity\Users\Reader;
use App\Repository\Books\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Book $bookId = null;

    #[ORM\Column]
    private ?bool $isRented = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Reader $readerId = null;

    public function __construct(
        Reader $readerId,
        Book $bookId
    ) {
        $this->readerId = $readerId;
        $this->bookId = $bookId;
        $this->isRented = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?Book
    {
        return $this->bookId;
    }

    public function setBookId(?Book $bookId): self
    {
        $this->bookId = $bookId;

        return $this;
    }

    public function getIsRented(): ?bool
    {
        return $this->isRented;
    }

    public function setIsRented(bool $isRented): self
    {
        $this->isRented = $isRented;

        return $this;
    }

    public function getReaderId(): ?Reader
    {
        return $this->readerId;
    }

    public function setReaderId(?Reader $readerId): self
    {
        $this->readerId = $readerId;

        return $this;
    }
}

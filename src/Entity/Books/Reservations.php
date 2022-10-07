<?php

namespace App\Entity\Books;

use App\Entity\Users\Readers;
use App\Repository\Books\ReservationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Books $bookId = null;

    #[ORM\Column]
    private ?bool $isRented = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Readers $readerId = null;

    public function __construct(
        Readers $readerId,
        Books $bookId
    ) {
        $this->readerId = $readerId;
        $this->bookId = $bookId;
        $this->isRented = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?Books
    {
        return $this->bookId;
    }

    public function setBookId(?Books $bookId): self
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

    public function getReaderId(): ?Readers
    {
        return $this->readerId;
    }

    public function setReaderId(?Readers $readerId): self
    {
        $this->readerId = $readerId;

        return $this;
    }
}

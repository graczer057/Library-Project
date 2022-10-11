<?php

namespace App\Entity\Books;

use App\Entity\Users\Reader;
use App\Repository\Books\RentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentRepository::class)]
class Rent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rent')]
    private ?Book $bookId = null;

    #[ORM\ManyToOne(inversedBy: 'rent')]
    private ?Reader $readerId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expireDate = null;

    #[ORM\Column]
    private ?bool $isReturned = null;

    public function __construct(
        Book $book,
        Reader $reader
    ) {
        $this->bookId = $book;
        $this->readerId = $reader;
        $date = new \DateTime("now");
        $this->expireDate = $date->modify("+ 7 days");
        $this->isReturned = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBookId(): ?Book
    {
        return $this->bookId;
    }

    public function setBookId(?Book $bookId): void
    {
        $this->bookId = $bookId;
    }

    public function getReaderId(): ?Reader
    {
        return $this->readerId;
    }

    public function setReaderId(?Reader $readerId): void
    {
        $this->readerId = $readerId;
    }
}

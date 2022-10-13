<?php

namespace App\Entity\Users;

use App\Entity\Books\Rent;
use App\Entity\Books\Reservation;
use App\Repository\Users\ReaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReaderRepository::class)]
class Reader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'readers', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column]
    private ?int $reservationsQuantity = null;

    #[ORM\OneToMany(mappedBy: 'readerId', targetEntity: Reservation::class)]
    private Collection $reservation;

    #[ORM\OneToMany(mappedBy: 'readerId', targetEntity: Rent::class)]
    private Collection $rent;

    public function __construct(
        User $user,
        int $reservationsQuantity
    ){
        $this->userId = $user;
        $this->name = null;
        $this->surname = null;
        $this->reservationsQuantity = $reservationsQuantity;
    }

    public function updateReader(
        string $name,
        string $surname
    ): self{
        $this->name = $name;
        $this->surname = $surname;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getReservationsQuantity(): ?int
    {
        return $this->reservationsQuantity;
    }

    public function setReservationsQuantity(int $reservationsQuantity): self
    {
        $this->reservationsQuantity = $reservationsQuantity;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setReaderId($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getReaderId() === $this) {
                $reservation->setReaderId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRent(): Collection
    {
        return $this->rent;
    }

    /**
     * @param Collection $rent
     */
    public function setRent(Collection $rent): void
    {
        $this->rent = $rent;
    }
}

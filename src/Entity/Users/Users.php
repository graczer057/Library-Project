<?php

namespace App\Entity\Users;

use App\Repository\Users\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $password;

    #[ORM\Column(length: 255)]
    private string $login;

    #[ORM\Column]
    private array $roles;

    #[ORM\Column]
    private bool $isActive;

    #[ORM\OneToOne(mappedBy: 'userId', cascade: ['persist', 'remove'])]
    private ?Admins $admins = null;

    #[ORM\OneToOne(mappedBy: 'userId', cascade: ['persist', 'remove'])]
    private ?Readers $readers = null;

    #[ORM\Column]
    private ?bool $isBanned = null;

    public function __construct(
        string $email,
        string $login,
        string $password,
        string $roles
    ) {
        $this->email = $email;
        $this->login = $login;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->roles[] = $roles;
        $this->isActive = false;
        $this->isBanned = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function isIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @throws Exception
     */
    public function getUserIdentifier(): string
    {
        if ($this->isBanned) {
            throw new Exception ('BAN');
        }

        if ($this->isActive) {
            return $this->login;
        } else {
            dd("Kompletacja użytkownika");
        }
    }

    public function getAdmins(): ?Admins
    {
        return $this->admins;
    }

    public function setAdmins(?Admins $admins): self
    {
        // unset the owning side of the relation if necessary
        if ($admins === null && $this->admins !== null) {
            $this->admins->setUserId(null);
        }

        // set the owning side of the relation if necessary
        if ($admins !== null && $admins->getUserId() !== $this) {
            $admins->setUserId($this);
        }

        $this->admins = $admins;

        return $this;
    }

    public function getReaders(): ?Readers
    {
        return $this->readers;
    }

    public function setReaders(Readers $readers): self
    {
        // set the owning side of the relation if necessary
        if ($readers->getUserId() !== $this) {
            $readers->setUserId($this);
        }

        $this->readers = $readers;

        return $this;
    }

    public function isIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

        return $this;
    }
}

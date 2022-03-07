<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email(message: 'Формат должен быть в виде email')]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    private $userName;

    #[ORM\OneToMany(mappedBy: 'uid', targetEntity: Specifications::class, orphanRemoval: true)]
    private $users;

    #[ORM\Column(type: 'integer',nullable: true)]
    private $idAccount;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $nameTelegram;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $commander;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $guild;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Specifications[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Specifications $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setUid($this);
        }

        return $this;
    }

    public function removeUser(Specifications $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUid() === $this) {
                $user->setUid(null);
            }
        }

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getIdAccount(): ?int
    {
        return $this->idAccount;
    }

    public function setIdAccount(int $idAccount): self
    {
        $this->idAccount = $idAccount;

        return $this;
    }


    public function getNameTelegram(): ?string
    {
        return $this->nameTelegram;
    }

    public function setNameTelegram(?string $nameTelegram): self
    {
        $this->nameTelegram = $nameTelegram;

        return $this;
    }

    public function getCommander(): ?string
    {
        return $this->commander;
    }

    public function setCommander(?string $commander): self
    {
        $this->commander = $commander;

        return $this;
    }

    public function getGuild(): ?string
    {
        return $this->guild;
    }

    public function setGuild(?string $guild): self
    {
        $this->guild = $guild;

        return $this;
    }



}

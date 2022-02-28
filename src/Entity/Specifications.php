<?php

namespace App\Entity;

use App\Repository\SpecificationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecificationsRepository::class)]
class Specifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer',nullable: 'true')]
    private $ip;

    #[ORM\Column(type: 'integer',nullable: 'true')]
    private $furniture;

    #[ORM\Column(type: 'integer',nullable: 'true')]
    private $engraving;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private $uid;

    #[ORM\ManyToOne(targetEntity: Hero::class, inversedBy: 'heros')]
    #[ORM\JoinColumn(nullable: false)]
    private $hid;

    #[ORM\Column(type: 'string', length: 255,nullable: 'true')]
    private $evolution;

    #[ORM\Column(type: 'datetime',nullable: 'true')]
    private $data;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?int
    {
        return $this->ip;
    }

    public function setIp(int $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getFurniture(): ?int
    {
        return $this->furniture;
    }

    public function setFurniture(int $furniture): self
    {
        $this->furniture = $furniture;

        return $this;
    }

    public function getEngraving(): ?int
    {
        return $this->engraving;
    }

    public function setEngraving(int $engraving): self
    {
        $this->engraving = $engraving;

        return $this;
    }

    public function getUid(): ?User
    {
        return $this->uid;
    }

    public function setUid(?User $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getHid(): ?Hero
    {
        return $this->hid;
    }

    public function setHid(?Hero $hid): self
    {
        $this->hid = $hid;

        return $this;
    }

    public function getEvolution(): ?string
    {
        return $this->evolution;
    }

    public function setEvolution(string $evolution): self
    {
        $this->evolution = $evolution;

        return $this;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }
}

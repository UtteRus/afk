<?php

namespace App\Entity;

use App\Repository\HireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HireRepository::class)]
class Hire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'hires')]
    #[ORM\JoinColumn(nullable: false)]
    private $uid;

    #[ORM\ManyToOne(targetEntity: Hero::class, inversedBy: 'hires')]
    #[ORM\JoinColumn(nullable: false)]
    private $hid;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $pumping;

    #[ORM\Column(type: 'boolean', nullable: true, options: ["default"=> 0] )]
    private $heroForHire;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPumping(): ?int
    {
        return $this->pumping;
    }

    public function setPumping(?int $pumping): self
    {
        $this->pumping = $pumping;

        return $this;
    }

    public function getHeroForHire(): ?bool
    {
        return $this->heroForHire;
    }

    public function setHeroForHire(?bool $heroForHire): self
    {
        $this->heroForHire = $heroForHire;

        return $this;
    }
}

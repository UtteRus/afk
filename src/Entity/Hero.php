<?php

namespace App\Entity;

use App\Repository\HeroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HeroRepository::class)]
class Hero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    private $heroName;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    #[Assert\NotBlank(message: 'это поле не должно быть пустым')]
    private $fraction;

    #[ORM\OneToMany(mappedBy: 'hid', targetEntity: Specifications::class, orphanRemoval: true)]
    private $heros;

    #[ORM\Column(type: 'float',nullable: true)]
    private $Velue;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $img;

    #[ORM\Column(type: 'integer', nullable: 'true')]
    private $ipRecommended;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $furnitureRecommended;

    #[ORM\Column(type: 'integer',nullable: true)]
    private $engravingRecommended;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $evolutionRecommended;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $pve;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $pvp;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $distortedWorld;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $events;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $abyss;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $general;

    public function __construct()
    {
        $this->heros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeroName(): ?string
    {
        return $this->heroName;
    }

    public function setHeroName(string $heroName): self
    {
        $this->heroName = $heroName;

        return $this;
    }

    public function getFraction(): ?string
    {
        return $this->fraction;
    }

    public function setFraction(string $fraction): self
    {
        $this->fraction = $fraction;

        return $this;
    }

    /**
     * @return Collection|Specifications[]
     */
    public function getHeros(): Collection
    {
        return $this->heros;
    }

    public function addHero(Specifications $hero): self
    {
        if (!$this->heros->contains($hero)) {
            $this->heros[] = $hero;
            $hero->setHid($this);
        }

        return $this;
    }

    public function removeHero(Specifications $hero): self
    {
        if ($this->heros->removeElement($hero)) {
            // set the owning side to null (unless already changed)
            if ($hero->getHid() === $this) {
                $hero->setHid(null);
            }
        }

        return $this;
    }

    public function getVelue(): ?float
    {
        return $this->Velue;
    }

    public function setVelue(float $Velue): self
    {
        $this->Velue = $Velue;

        return $this;
    }

    public function getImg(): ?string
    {
        return '/img/hero/'.$this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getIpRecommended(): ?int
    {
        return $this->ipRecommended;
    }

    public function setIpRecommended(int $ipRecommended): self
    {
        $this->ipRecommended = $ipRecommended;

        return $this;
    }

    public function getFurnitureRecommended(): ?int
    {
        return $this->furnitureRecommended;
    }

    public function setFurnitureRecommended(int $furnitureRecommended): self
    {
        $this->furnitureRecommended = $furnitureRecommended;

        return $this;
    }

    public function getEngravingRecommended(): ?int
    {
        return $this->engravingRecommended;
    }

    public function setEngravingRecommended(int $engravingRecommended): self
    {
        $this->engravingRecommended = $engravingRecommended;

        return $this;
    }

    public function getEvolutionRecommended(): ?string
    {
        return $this->evolutionRecommended;
    }

    public function setEvolutionRecommended(?string $evolutionRecommended): self
    {
        $this->evolutionRecommended = $evolutionRecommended;

        return $this;
    }

    public function getPve(): ?string
    {
        return $this->pve;
    }

    public function setPve(?string $pve): self
    {
        $this->pve = $pve;

        return $this;
    }

    public function getPvp(): ?string
    {
        return $this->pvp;
    }

    public function setPvp(?string $pvp): self
    {
        $this->pvp = $pvp;

        return $this;
    }

    public function getDistortedWorld(): ?string
    {
        return $this->distortedWorld;
    }

    public function setDistortedWorld(?string $distortedWorld): self
    {
        $this->distortedWorld = $distortedWorld;

        return $this;
    }

    public function getEvents(): ?string
    {
        return $this->events;
    }

    public function setEvents(?string $events): self
    {
        $this->events = $events;

        return $this;
    }

    public function getAbyss(): ?string
    {
        return $this->abyss;
    }

    public function setAbyss(?string $abyss): self
    {
        $this->abyss = $abyss;

        return $this;
    }

    public function getGeneral(): ?string
    {
        return $this->general;
    }

    public function setGeneral(?string $general): self
    {
        $this->general = $general;

        return $this;
    }
}

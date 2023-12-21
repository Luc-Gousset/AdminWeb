<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $nomRue = null;

    #[ORM\OneToMany(mappedBy: 'AdresseHabitant', targetEntity: Habitant::class)]
    private Collection $habitants;

    public function __construct()
    {
        $this->habitants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getNomRue(): ?string
    {
        return $this->nomRue;
    }

    public function setNomRue(string $nomRue): static
    {
        $this->nomRue = $nomRue;

        return $this;
    }

    /**
     * @return Collection<int, Habitant>
     */
    public function getHabitants(): Collection
    {
        return $this->habitants;
    }

    public function addHabitant(Habitant $habitant): static
    {
        if (!$this->habitants->contains($habitant)) {
            $this->habitants->add($habitant);
            $habitant->setAdresseHabitant($this);
        }

        return $this;
    }

    public function removeHabitant(Habitant $habitant): static
    {
        if ($this->habitants->removeElement($habitant)) {
            // set the owning side to null (unless already changed)
            if ($habitant->getAdresseHabitant() === $this) {
                $habitant->setAdresseHabitant(null);
            }
        }

        return $this;
    }
}

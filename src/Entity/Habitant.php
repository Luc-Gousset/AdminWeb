<?php

namespace App\Entity;

use App\Repository\HabitantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HabitantRepository::class)]
class Habitant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'Le nom doit faire au moins {{ limit }} caractères', maxMessage: 'Le nom doit faire moins de {{ limit }} caractères')]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'Le prénom doit faire au moins {{ limit }} caractères', maxMessage: 'Le prénom doit faire moins de {{ limit }} caractères')]
    private ?string $Prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La date de naissance est obligatoire')]
    private ?\DateTimeInterface $DateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le genre est obligatoire')]
    #[Assert\Choice(choices: ['homme', 'femme', 'autre'], message: 'Le genre doit être homme, femme ou autre')]
    private ?string $Genre = null;

    
    #[ORM\ManyToOne(inversedBy: 'habitants')]
    private ?Adresse $AdresseHabitant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->DateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $DateNaissance): static
    {
        $this->DateNaissance = $DateNaissance;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->Genre;
    }

    public function setGenre(string $Genre): static
    {
        $this->Genre = $Genre;

        return $this;
    }

    public function getAdresseHabitant(): ?Adresse
    {
        return $this->AdresseHabitant;
    }

    public function setAdresseHabitant(?Adresse $AdresseHabitant): static
    {
        $this->AdresseHabitant = $AdresseHabitant;

        return $this;
    }
}

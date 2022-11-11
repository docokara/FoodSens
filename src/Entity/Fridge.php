<?php

namespace App\Entity;

use App\Repository\FridgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FridgeRepository::class)
 */
class Fridge
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Ingredient::class)
     */
    private $ingredients;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="fridge", cascade={"persist", "remove"})
     */
    private $owner;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }
    public function isInFridge($array): bool
    {
        $contain = false;
        foreach ($this->getIngredients() as $fridgeIngredient) {
            foreach ($array as $ingredient) {
                if ($ingredient->getId() == $fridgeIngredient->getId()) {
                    $contain = true;
                }
            }
        }
        return $contain;
    }
    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}

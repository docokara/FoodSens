<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=IngredientCategorie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $kcalFor100g;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PriceFor100g;

    /**
     * @ORM\ManyToMany(targetEntity=Recipe::class, mappedBy="ingredients")
     */
    private $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?IngredientCategorie
    {
        return $this->type;
    }

    public function setType(?IngredientCategorie $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getKcalFor100g(): ?string
    {
        return $this->kcalFor100g;
    }

    public function setKcalFor100g(string $kcalFor100g): self
    {
        $this->kcalFor100g = $kcalFor100g;

        return $this;
    }

    public function getPriceFor100g(): ?string
    {
        return $this->PriceFor100g;
    }

    public function setPriceFor100g(string $PriceFor100g): self
    {
        $this->PriceFor100g = $PriceFor100g;

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->addIngredient($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeIngredient($this);
        }

        return $this;
    }
    public function __toString() : string
    {
    return $this->name;
    }
}

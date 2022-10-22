<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $tags = [];

    /**
     * @ORM\Column(type="json")
     */
    private $steps = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $people;

    /**
     * @ORM\Column(type="integer")
     */
    private $budget;

    /**
     * @ORM\Column(type="integer")
     */
    private $difficulty;

    /**
     * @ORM\Column(type="integer")
     */
    private $preptime;

    /**
     * @ORM\Column(type="integer")
     */
    private $toltalTime;

    /**
     * @ORM\ManyToMany(targetEntity=Ingredient::class, inversedBy="recipes")
     */
    private $ingredients;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
     */
    private $Author;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getSteps(): ?array
    {
        return $this->steps;
    }

    public function setSteps(array $steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    public function getPeople(): ?int
    {
        return $this->people;
    }

    public function setPeople(int $people): self
    {
        $this->people = $people;

        return $this;
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(int $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getPreptime(): ?int
    {
        return $this->preptime;
    }

    public function setPreptime(int $preptime): self
    {
        $this->preptime = $preptime;

        return $this;
    }

    public function getToltalTime(): ?int
    {
        return $this->toltalTime;
    }

    public function setToltalTime(int $toltalTime): self
    {
        $this->toltalTime = $toltalTime;

        return $this;
    }


    /**
     * @return Collection<int, ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?User $Author): self
    {
        $this->Author = $Author;

        return $this;
    }
}

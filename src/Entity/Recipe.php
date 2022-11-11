<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $steps;

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
     * @ORM\ManyToMany(targetEntity=Ingredient::class, inversedBy="recipes", cascade={"persist"})
     */
    private $ingredients;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
     */
    private $Author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favories")
     */
    private $fav;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="location")
     */
    private $commentaires;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->fav = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags($tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getSteps(): ?string
    {
        return $this->steps;
    }

    public function setSteps($steps): self
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
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }
    public function containIngredients($array): bool
    {
        $contain = false;
        foreach ($this->getIngredients() as $ingredient) {
            foreach ($array as $id) {
                if ($id == $ingredient->getId()) {
                    $contain = true;
                }
            }
        }
        return $contain;
    }
    public function containIngredientsCategorie($array): bool
    {
        $contain = false;
        foreach ($this->getIngredients() as $ingredient) {
            foreach ($array as $id) {
                if ($id == $ingredient->getType()->getId()) {
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

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?User $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
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

    /**
     * @return Collection<int, User>
     */
    public function getFav(): Collection
    {
        return $this->fav;
    }

    public function addFav(User $fav): self
    {
        if (!$this->fav->contains($fav)) {
            $this->fav[] = $fav;
        }

        return $this;
    }

    public function removeFav(User $fav): self
    {
        $this->fav->removeElement($fav);

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setLocation($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getLocation() === $this) {
                $commentaire->setLocation(null);
            }
        }

        return $this;
    }
}

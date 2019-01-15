<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $calory;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $protein;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $carbohydrate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="recipe")
     */
    private $images;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\RecipeRepost", mappedBy="recipe", cascade={"persist", "remove"})
     */
    private $recipe;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favorite", mappedBy="recipe")
     */
    private $recipeFavorite;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->recipeFavorite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalory(): ?int
    {
        return $this->calory;
    }

    public function setCalory(int $calory): self
    {
        $this->calory = $calory;

        return $this;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(?int $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    public function getCarbohydrate(): ?int
    {
        return $this->carbohydrate;
    }

    public function setCarbohydrate(?int $carbohydrate): self
    {
        $this->carbohydrate = $carbohydrate;

        return $this;
    }

    public function getFat(): ?int
    {
        return $this->fat;
    }

    public function setFat(?int $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setRecipe($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getRecipe() === $this) {
                $image->setRecipe(null);
            }
        }

        return $this;
    }

    public function getRecipe(): ?RecipeRepost
    {
        return $this->recipe;
    }

    public function setRecipe(RecipeRepost $recipe): self
    {
        $this->recipe = $recipe;

        // set the owning side of the relation if necessary
        if ($this !== $recipe->getRecipe()) {
            $recipe->setRecipe($this);
        }

        return $this;
    }

    /**
     * @return Collection|Favorite[]
     */
    public function getRecipeFavorite(): Collection
    {
        return $this->recipeFavorite;
    }

    public function addRecipeFavorite(Favorite $recipeFavorite): self
    {
        if (!$this->recipeFavorite->contains($recipeFavorite)) {
            $this->recipeFavorite[] = $recipeFavorite;
            $recipeFavorite->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeFavorite(Favorite $recipeFavorite): self
    {
        if ($this->recipeFavorite->contains($recipeFavorite)) {
            $this->recipeFavorite->removeElement($recipeFavorite);
            // set the owning side to null (unless already changed)
            if ($recipeFavorite->getRecipe() === $this) {
                $recipeFavorite->setRecipe(null);
            }
        }

        return $this;
    }
}

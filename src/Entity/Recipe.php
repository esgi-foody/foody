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
     * @ORM\Column(type="string",  length=255, nullable=false)
     */
    private $title;

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
     * @ORM\OneToMany(targetEntity="App\Entity\RecipeStep", mappedBy="recipe")
     */
    private $recipeSteps;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingredient", mappedBy="recipe")
     */
    private $ingredients;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userRecipe;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Like", mappedBy="recipe")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="recipe")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pathCoverImg;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", mappedBy="recipe")
     */
    private $categories;

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

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    public function __construct()
    {
        $this->recipeSteps = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
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
     * @return Collection|RecipeStep[]
     */
    public function getRecipeSteps(): Collection
    {
        return $this->recipeSteps;
    }

    public function addRecipeStep(RecipeStep $recipeStep): self
    {
        if (!$this->recipeSteps->contains($recipeStep)) {
            $this->recipeSteps[] = $recipeStep;
            $recipeStep->setRecipe($this);
        }
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

    public function removeRecipeStep(RecipeStep $recipeStep): self
    {
        if ($this->recipeSteps->contains($recipeStep)) {
            $this->recipeSteps->removeElement($recipeStep);
            // set the owning side to null (unless already changed)
            if ($recipeStep->getRecipe() === $this) {
                $recipeStep->setRecipe(null);
            }
        }
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

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }

    public function getUserRecipe(): ?User
    {
        return $this->userRecipe;
    }

    public function setUserRecipe(?User $userRecipe): self
    {
        $this->userRecipe = $userRecipe;

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setRecipe($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getRecipe() === $this) {
                $like->setRecipe(null);
            }
        }
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
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRecipe($this);
        }
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

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }
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

    public function getPathCoverImg(): ?string
    {
        return $this->pathCoverImg;
    }

    public function setPathCoverImg(string $pathCoverImg): self
    {
        $this->pathCoverImg = $pathCoverImg;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addRecipe($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeRecipe($this);
        }

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }
    
}

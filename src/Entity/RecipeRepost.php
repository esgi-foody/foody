<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepostRepository")
 */
class RecipeRepost
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reporterUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reporterUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="recipe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReporterUser(): ?User
    {
        return $this->reporterUser;
    }

    public function setReporterUser(?User $reporterUser): self
    {
        $this->reporterUser = $reporterUser;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }
}

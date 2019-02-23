<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Table(name="favorite")
 * @UniqueEntity(fields={"userFavorite", "recipe"})
 * @ORM\Entity(repositoryClass="App\Repository\FavoriteRepository")
 */
class Favorite
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userFavorite")
     * @ORM\JoinColumn(referencedColumnName="id",nullable=false)
     */
    private $userFavorite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="recipeFavorite", fetch="EAGER")
     * @ORM\JoinColumn(referencedColumnName="id",nullable=false)
     */
    private $recipe;
FavoriteRepository
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserFavorite(): ?User
    {
        return $this->userFavorite;
    }

    public function setUserFavorite(?User $userFavorite): self
    {
        $this->userFavorite = $userFavorite;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }
}

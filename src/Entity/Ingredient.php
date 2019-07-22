<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 */
class Ingredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Le nom doit etre renseigné")
     * @ORM\Column(type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="La quantité doit etre renseignée")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $measuringUnit;

    /**
     * @Assert\NotBlank(message="La quantité de protéine doit etre renseignée")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $protein;

    /**
     * @Assert\NotBlank(message="La quantité de glucide doit etre renseignée")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $carbohydrate;

    /**
     * @Assert\NotBlank(message="La quantité de lipide doit etre renseignée")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $fat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;

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

    public function getMeasuringUnit(): ?string
    {
        return $this->measuringUnit;
    }

    public function setMeasuringUnit(string $measuringUnit): self
    {
        $this->measuringUnit = $measuringUnit;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(int $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    public function getCarbohydrate(): ?int
    {
        return $this->carbohydrate;
    }

    public function setCarbohydrate(int $carbohydrate): self
    {
        $this->carbohydrate = $carbohydrate;

        return $this;
    }

    public function getFat(): ?int
    {
        return $this->fat;
    }

    public function setFat(int $fat): self
    {
        $this->fat = $fat;

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

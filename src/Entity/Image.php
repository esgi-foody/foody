<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pathImg;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecipeStep", inversedBy="image")
     */
    private $recipeStep;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    public function setPathImg(string $pathImg): self
    {
        $this->pathImg = $pathImg;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRecipeStep(): ?RecipeStep
    {
        return $this->recipeStep;
    }

    public function setRecipeStep(?RecipeStep $recipeStep): self
    {
        $this->recipeStep = $recipeStep;

        return $this;
    }
}

<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user_account")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé")
 * @UniqueEntity(fields={"username"}, message="Ce nom d'utilisateur est déjà utilisé")
 */
class User implements UserInterface
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $password;

    private $plainPassword;

    /**
     * @ORM\Column(type="date")
     * @Assert\LessThanOrEqual("today")
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pathImg;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Relationship", mappedBy="follower", cascade={"persist"},orphanRemoval=true, fetch="EAGER")
     * @MaxDepth(1)
     */
    private $followeds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Relationship", mappedBy="followed", cascade={"persist"},orphanRemoval=true, fetch="EAGER")
     * @MaxDepth(1)
     */
    private $followers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recipe", mappedBy="userRecipe")
     */
    private $recipes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Like", mappedBy="likerUser")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="commentator")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Report", mappedBy="report")
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecipeRepost", mappedBy="reporterUser")
     */
    private $recipeReposts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favorite", mappedBy="userFavorite")
     */
    private $userFavorite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $biography;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $status=0;

    public function __construct()
    {
        $this->followeds = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->recipes = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->recipeReposts = new ArrayCollection();
        $this->userFavorite = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    public function setPathImg(?string $pathImg): self
    {
        $this->pathImg = $pathImg;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Relationship[]
     */
    public function getFolloweds(): Collection
    {
        return $this->followeds;
    }

    public function addFollowed(Relationship $followed): self
    {
        if (!$this->followeds->contains($followed)) {
            $this->followeds[] = $followed;
            $followed->setFollowed($this);
        }

        return $this;
    }

    public function removeFollowed(Relationship $followed): self
    {
        if ($this->followeds->contains($followed)) {
            $this->followeds->removeElement($followed);
            // set the owning side to null (unless already changed)
            if ($followed->getFollowed() === $this) {
                $followed->setFollowed(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Relationship[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Relationship $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
            $follower->setFollower($this);
        }

        return $this;
    }

    public function removeFollower(Relationship $follower): self
    {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
            // set the owning side to null (unless already changed)
            if ($follower->getFollower() === $this) {
                $follower->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setUserRecipe($this);
        }
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setReport($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            // set the owning side to null (unless already changed)
            if ($recipe->getUserRecipe() === $this) {
                $recipe->setUserRecipe(null);
            }
        }
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->contains($report)) {
            $this->reports->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getReport() === $this) {
                $report->setReport(null);

            }
        }

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
            $like->setLiker($this);
        }

        return $this;
    }

    /**
     * @return Collection|RecipeRepost[]
     */
    public function getRecipeReposts(): Collection
    {
        return $this->recipeReposts;
    }

    public function addRecipeRepost(RecipeRepost $recipeRepost): self
    {
        if (!$this->recipeReposts->contains($recipeRepost)) {
            $this->recipeReposts[] = $recipeRepost;
            $recipeRepost->setReporter($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getLiker() === $this) {
                $like->setLiker(null);
            }
        }
    }

    public function removeRecipeRepost(RecipeRepost $recipeRepost): self
    {
        if ($this->recipeReposts->contains($recipeRepost)) {
            $this->recipeReposts->removeElement($recipeRepost);
            // set the owning side to null (unless already changed)
            if ($recipeRepost->getReporter() === $this) {
                $recipeRepost->setReporter(null);

            }
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
            $comment->setCommentator($this);
        }
    }

    /**
     * @return Collection|Favorite[]
     */
    public function getUserFavorite(): Collection
    {
        return $this->userFavorite;
    }

    public function addUserFavorite(Favorite $userFavorite): self
    {
        if (!$this->userFavorite->contains($userFavorite)) {
            $this->userFavorite[] = $userFavorite;
            $userFavorite->setUserFavorite($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCommentator() === $this) {
                $comment->setCommentator(null);
            }
        }
    }

    public function removeUserFavorite(Favorite $userFavorite): self
    {
        if ($this->userFavorite->contains($userFavorite)) {
            $this->userFavorite->removeElement($userFavorite);
            // set the owning side to null (unless already changed)
            if ($userFavorite->getUserFavorite() === $this) {
                $userFavorite->setUserFavorite(null);
            }
        }

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;

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

}

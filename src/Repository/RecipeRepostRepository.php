<?php

namespace App\Repository;

use App\Entity\RecipeRepost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RecipeRepost|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeRepost|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeRepost[]    findAll()
 * @method RecipeRepost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RecipeRepost::class);
    }

    public function findRecipeRepostByUser($idRecipe)
    {

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
           SELECT u
           FROM App\Entity\User u,App\Entity\RecipeRepost r
           WHERE r.reporter  = u.id
           AND r.recipe  = :idRecipe
           ')->setParameter('idRecipe', $idRecipe);

        return $query->execute();
    }
}

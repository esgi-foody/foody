<?php

namespace App\Repository;

use App\Entity\Favorite;
use App\Entity\User;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Favorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favorite[]    findAll()
 * @method Favorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Favorite::class);
    }

    public function findFavoritesByUser(User $user)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
           SELECT r FROM App\Entity\Recipe r 
           JOIN App\Entity\Favorite f 
           WITH f.recipe = r.id 
           WHERE f.userFavorite = :userId
            ')->setParameter('userId', $user->getId());
        return $query->execute();
    }
}

//


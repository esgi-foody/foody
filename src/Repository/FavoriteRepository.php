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


    public function findFavoritesByUser(User $user): ?Collection
    {
<<<<<<< Updated upstream
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
            SELECT DISTINCT r
            FROM App\Entity\Recipe r, App\Entity\Favorite f, App\Entity\User u
            WHERE  f.userFavorite = :userId
            AND  f.follower = l.liker
            ORDER BY r.createdAt DESC
            ')->setParameter('userId', $user->getId());

        return $query->execute();

    }
=======
        return$this->createQueryBuilder('q')
            ->from('recipe','r')
            ->innerJoin('r.recipe', 'recipe')
            ->where('user.id LIKE :userId')
            ->setParameters(['userId' => $user->getId()])
            ->getQuery()
            ->getResult();
    }

>>>>>>> Stashed changes
}

//SELECT * FROM Recipe WHERE recipe.id = SELECT recipeId FROM favorite WHERE favorite.UserId =:UserId

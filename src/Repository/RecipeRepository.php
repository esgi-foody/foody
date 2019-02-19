<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function findByTitle($query)
    {
        return $this->createQueryBuilder('r')
            ->where('r.title LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $userId
     * @param $limit
     * @return array
     */
    public function findByUserSuggestion($userId, $limit)
    {

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
            SELECT DISTINCT r
            FROM App\Entity\Recipe r, App\Entity\Relationship f, App\Entity\Like l
            WHERE  f.follower = r.userRecipe
            AND  f.follower = l.liker
            AND  l.liker != :userId
            AND  r.userRecipe != :userId
            ORDER BY r.createdAt DESC
            ')->setParameter('userId' , $userId )->setMaxResults($limit);
        return $query->execute();
    }

    /**
     * @param $query
     * @param $categories
     * @return array
     */
    public function findByCategory($query, $categories)
    {
        $categoriesId = [];
        foreach ($categories as $category) {
            $categoriesId[] = $category->getId();
        }

        $qb = $this->createQueryBuilder('r');
        return $qb->addSelect('r')
            ->innerJoin('r.categories', 'c')
            ->where('r.title LIKE :query')
            ->andWhere('c.id IN (:id)')
            ->setParameters([ 'query' => '%' . $query . '%', 'id' => $categoriesId ])
            ->getQuery()
            ->getResult();
    }
}

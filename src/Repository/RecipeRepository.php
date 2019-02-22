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

    /**
     * RecipeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recipe::class);
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
     * @param $data
     * @return \Doctrine\ORM\QueryBuilder|mixed
     */
    public function findWithFilters($data, $categories = null)
    {
        $qb = $this->createQueryBuilder('r');
        $qb = $qb->addSelect('r')
            ->innerJoin('r.categories', 'c')
            ->where('r.title LIKE :query')
            ->setParameter('query', '%' . $data['query'] . '%');
        if ($categories) {
            $qb = $qb->andWhere('c.id IN (:id)')
                ->setParameter('id', $categories);
        }
        if ($data['calorie_min']) {
            $qb = $qb->andWhere('r.calory >= :calorie_min')
                ->setParameter('calorie_min', $data['calorie_min']);
        }
        if ($data['calorie_max']) {
            $qb = $qb->andWhere('r.calory <= :calorie_max')
                ->setParameter('calorie_max', $data['calorie_max']);
        }
        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findHighestCalorie()
    {
        return $this->createQueryBuilder('r')
            ->select('MAX(r.calory)')
            ->getQuery()
            ->getSingleResult();
    }
}

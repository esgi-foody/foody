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
        if ($data['protein_min']) {
            $qb = $qb->andWhere('r.protein >= :protein_min')
                ->setParameter('protein_min', $data['protein_min']);
        }
        if ($data['protein_max']) {
            $qb = $qb->andWhere('r.protein <= :protein_max')
                ->setParameter('protein_max', $data['protein_max']);
        }
        if ($data['carbohydrate_min']) {
            $qb = $qb->andWhere('r.carbohydrate >= :carbohydrate_min')
                ->setParameter('carbohydrate_min', $data['carbohydrate_min']);
        }
        if ($data['carbohydrate_max']) {
            $qb = $qb->andWhere('r.carbohydrate <= :carbohydrate_max')
                ->setParameter('carbohydrate_max', $data['carbohydrate_max']);
        }
        if ($data['fat_min']) {
            $qb = $qb->andWhere('r.fat >= :fat_min')
                ->setParameter('fat_min', $data['fat_min']);
        }
        if ($data['fat_max']) {
            $qb = $qb->andWhere('r.fat <= :fat_max')
                ->setParameter('fat_max', $data['fat_max']);
        }
        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param $data
     * @return \Doctrine\ORM\QueryBuilder|mixed
     */
    public function findWithFolloweds($userId, $limit)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
            SELECT DISTINCT r
            FROM App\Entity\Recipe r, App\Entity\Relationship f
            WHERE  f.follower = :userId
            AND f.followed = r.userRecipe
            AND  r.userRecipe != :userId
            ORDER BY r.createdAt DESC
            ')->setParameter('userId' , $userId )->setMaxResults($limit);

       /* '

            SELECT r
            FROM App\Entity\Recipe r,
                 App\Entity\Relationship f
            JOIN f.follower flr
            WHERE r.userRecipe = flr
            AND f.followed = :userId

            SELECT DISTINCT r
            FROM App\Entity\Recipe r, App\Entity\Relationship f
            WHERE  f.follower = :userId
            AND f.followed = r.userRecipe
            AND  r.userRecipe != :userId
            ORDER BY r.createdAt DESC
            '*/
        return $query->execute();
    }
}

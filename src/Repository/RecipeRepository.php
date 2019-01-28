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

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

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
            ->getResult()
        ;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function findByCategory($query, $category)
    {
        $articleIds = [1,2,3,4,5];

        $qb = $this->createQueryBuilder('r');

        return $qb->addSelect('r')
            ->innerJoin('r.categories', 'c')
            ->add('where', $qb->expr()->in('c', $articleIds))
            ->getQuery()
            ->getResult();
    }
}

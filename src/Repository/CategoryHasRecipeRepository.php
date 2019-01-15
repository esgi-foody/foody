<?php

namespace App\Repository;

use App\Entity\CategoryHasRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategoryHasRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryHasRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryHasRecipe[]    findAll()
 * @method CategoryHasRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryHasRecipeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategoryHasRecipe::class);
    }

    // /**
    //  * @return CategoryHasRecipe[] Returns an array of CategoryHasRecipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryHasRecipe
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

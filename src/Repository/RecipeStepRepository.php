<?php

namespace App\Repository;

use App\Entity\RecipeStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RecipeStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeStep[]    findAll()
 * @method RecipeStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeStepRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RecipeStep::class);
    }

    // /**
    //  * @return RecipeStep[] Returns an array of RecipeStep objects
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

    /*
    public function findOneBySomeField($value): ?RecipeStep
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

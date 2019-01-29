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
     * @param $categories
     * @return array
     */
    public function findByCategory($query, $categories)
    {
        $results = [];
        $recipes = [];

        foreach ($categories as $category) {
            $qb = $this->createQueryBuilder('r');
            $recipes[] = $qb->addSelect('r')
                ->innerJoin('r.categories', 'c')
                ->where('r.title LIKE :query')
                ->andWhere('c.id = :id')
                ->setParameters([ 'query' => '%' . $query . '%', 'id' => $category->getId() ])
                ->getQuery()
                ->getResult();
        }

        foreach ($recipes as $recipe) {
            foreach ($recipe as $value) {
                $results[] = $value;
            }
        }

        return $results;
    }
}

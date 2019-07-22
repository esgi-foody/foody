<?php

namespace App\Repository;

use App\Entity\Relationship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Relationship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relationship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relationship[]    findAll()
 * @method Relationship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelationshipRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Relationship::class);
    }

    public function findOneById($id1 , $id2)
    {
        return $this->createQueryBuilder('relation')
            ->andWhere('relation.follower = :id1')
            ->andWhere('relation.followed = :id2')
            ->setParameter('id1', $id1)
            ->setParameter('id2', $id2)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY)
            ;
    }

}

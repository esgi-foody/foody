<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notification::class);
    }

     /**
      * @return Notification[] Returns an array of Notification objects
      */

    public function countUserNotifications($user)
    {

        $qb = $this->createQueryBuilder('n');
        return $qb->addSelect('Count(n)')
            ->where('n.sender = :sender')
            ->setParameter('sender',$user)
            ->getQuery()
            ->getResult();
    }


}

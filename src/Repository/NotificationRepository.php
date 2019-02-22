<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
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
      * @return int Returns a number of user Notification
      * @param User $user
      */

    public function countUserNotifications($user)
    {

        $qb = $this->createQueryBuilder('n');
        return $qb->select('Count(n)')
            ->where('n.receiver = :receiver')
            ->andWhere('n.seen = false')
            ->setParameter('receiver',$user)
            ->getQuery()
            ->getSingleScalarResult();
    }

     /**
      * @return Notification[] Returns an array of object
      * @param int $userId
      * @param int $limit
      */

    public function userNotifications($userId,$limit)
    {

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
            SELECT n.message,  n.type,  n.link, u.username,u.imageName
            FROM App\Entity\Notification n, App\Entity\User u
            WHERE  n.receiver = :userId
            AND  u.id = n.sender
            ORDER BY n.createdAt DESC
            ')->setParameter('userId' , $userId )->setMaxResults($limit);
        return $query->execute();
    }

     /**
      * @return Notification[] Returns an array of object
      * @param int $userId
      * @param int $limit
      */

    public function updateNotificationsSeen($userId)
    {

        $qb = $this->createQueryBuilder('n');

        $query = $qb->update()
            ->set('n.seen', ':seen')
            ->where('n.receiver= :userId')
            ->setParameters(['userId'=> $userId ,'seen' => true])
            ->getQuery();

        return $query->execute();

    }


}

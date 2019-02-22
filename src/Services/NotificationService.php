<?php

namespace App\Services;

use App\Repository\NotificationRepository;
use App\Entity\Notification;
use Symfony\Component\Security\Core\Security;

class NotificationService
{
    private $notificationRepository;
    private $sender;

    /**
     * Notification constructor.
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(Security $security,NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
        $this->sender = $security->getUser();

    }


    public function sendNotification($receiver,$message,$type,$link)
    {
        $notification = new Notification();
        $notification->setSender($this->sender);
        $notification->setReceiver($receiver);
        $notification->setMessage($message);
        $notification->setType($type);
        $notification->setLink($link);

        $em = $this->getDoctrine()->getManager();
        $em->persist($notification);
        $em->flush();

      dump($this->sender);die;
    }


    public function countNotifications(NotificationRepository $notificationRepository)
    {
        return $notificationRepository->countUserNotifications($this->sender);
    }

    public function senderNotifications(NotificationRepository $notificationRepository)
    {
        $notificationRepository->findBy(['sender' => $this->sender],['createdAt'=>'DESC'],50);
      dump($this->sender);die;
    }
}

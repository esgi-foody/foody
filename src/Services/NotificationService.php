<?php

namespace App\Services;

use App\Repository\NotificationRepository;
use App\Entity\Notification;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ObjectManager;

class NotificationService
{
    private $notificationRepository;
    private $sender;

    /**
     * Notification constructor.
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(Security $security,NotificationRepository $notificationRepository ,ObjectManager $om)
    {
        $this->notificationRepository = $notificationRepository;
        $this->sender = $security->getUser();
        $this->om = $om;

    }

    public function sendNotification($receiver,$message,$type,$link)
    {
        $notification = new Notification();
        $notification->setSender($this->sender);
        $notification->setReceiver($receiver);
        $notification->setMessage($message);
        $notification->setType($type);
        $notification->setSeen(0);
        $notification->setLink($link);

        $this->om->persist($notification);
    }


    public function countNotifications()
    {
        return $this->notificationRepository->countUserNotifications($this->sender);
    }
}

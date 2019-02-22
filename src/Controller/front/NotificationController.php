<?php

namespace App\Controller\front;

use App\Form\NotificationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\NotificationService;
use App\Repository\NotificationRepository;

/**
 * Class NotificationController
 * @package App\Controller
 * @Route("/notifications", name="app_notifications_")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(NotificationRepository $notificationRepository)
    {
        $notifications = $notificationRepository->userNotifications($this->getUser(),100);
        $notificationRepository->updateNotificationsSeen($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->render('front/notification/index.html.twig', ['notifications' => $notifications]);
    }
}

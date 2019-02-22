<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Services\NotificationService;

class NotificationExtension extends AbstractExtension {

    private $notificationService;

    /**
     * NotificationExtension constructor.
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;

    }

    public function getFunctions()
    {
        return [
            new TwigFunction('notificationsNumber', [$this, 'countNotifications']),
        ];
    }

    public function countNotifications()
    {
        return $this->notificationService->countNotifications();
    }
}

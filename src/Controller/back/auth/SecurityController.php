<?php

namespace App\Controller\back\auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route(name="app_back_auth_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(AuthenticationUtils $helper): Response
    {
        return $this->render('back/auth/admin.html.twig', [
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }
}
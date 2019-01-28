<?php

namespace App\Controller\front;

use App\Entity\User;
use App\Entity\Relationship;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Provider\RememberMeAuthenticationProvider;

/**
 * @Route("/profile", name="app_front_")
 */

class ProfileController extends AbstractController
{
    /**
     * @Route("/{username}", name="profile_show", methods="GET")
     */
    public function show(User $user): Response
    {
        return $this->render('front/profile/index.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{username}/follow", name="profile_follow", methods="GET")
     */
    public function follow(User $user): Void_
    {

        $relation = new Relationship();
        $relation->setFollowed($user);
        $relation->setFollower($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($relation);
        $em->flush();

        return $this->redirectToRoute('app_front_profile_follow');
    }

    /**
     * @Route("/{username}/unfollow", name="user_unfollow", methods="GET|POST")
     */
    public function unfollow(): Response
    {

    }
}

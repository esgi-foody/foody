<?php

namespace App\Controller\front;

use App\Entity\User;
use App\Entity\Relationship;
use App\Repository\UserRepository;
use App\Repository\RelationshipRepository;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Void_;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\DependencyInjection\Compiler\ResolveBindingsPass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
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

        $em = $this->getDoctrine()->getManager();
        if ($em->getRepository(Relationship::class)->findOneById($this->getUser()->getId(),$user->getId())){
            $followBtn = ['title'=>'Ne plus suivre','path'=>'profile_unfollow'];
        } else {
            $followBtn = ['title'=>'Suivre','path'=>'profile_follow'];
        }
        return $this->render('front/profile/index.html.twig', ['user' => $user , 'follow' => $followBtn]);
    }

    /**
     * @Route("/{username}/follow", name="profile_follow", methods="GET|POST")
     */
    public function follow(User $user): Response
    {

        $relation = new Relationship();
        $relation->setFollowed($user);
        $relation->setFollower($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($relation);
        $em->flush();

        return $this->redirectToRoute('app_front_profile_show',['username'=> $user->getUsername()]);
    }

    /**
     * @Route("/{username}/unfollow", name="profile_unfollow", methods="GET|POST")
     * @param User $user
     * @return Response
     */
    public function unfollow(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $follower = $this->getUser();

        $relation = $em->getRepository(Relationship::class)->findOneById($follower->getId(),$user->getId());
        $em->remove($em->getRepository(Relationship::class)->find($relation[0]['id']));
        $em->flush();

        return $this->redirectToRoute('app_front_profile_show',['username'=> $user->getUsername()]);
    }

}

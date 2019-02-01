<?php

namespace App\Controller\front;

use App\Entity\User;
use App\Entity\Relationship;
use App\Repository\UserRepository;
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

        $user = $this->getUser();
        var_dump($user);die();

        return $this->render('front/profile/index.html.twig', ['user' => $user]);

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
     * @Route("/{username}/unfollow", name="user_unfollow", methods="GET|POST")
     */
    public function unfollow(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $follower = $this->getUser();
        $relation = $em->getRepository(Relationship::class)->findBy(['followed_id' => $user->getId()], ['follower_id' => $follower->getId()]);

        $em->remove($relation);
        $em->flush();

        return $this->redirectToRoute('app_front_profile_show',['username'=> $user->getUsername()]);
    }

    /**
     * @Route("/{username}/isfollower", name="isfollower", methods="GET|POST")
     */
    public function isFollower(User $user): Response
    {
        $currentUser = $this->getUser();
//        $currentUser->getFolloweds();
//        $currentUser->getFollowers();
        $response = new Response();


//        if ( in_array($user,$followed)){
//            $response->setContent('TRUE');
//        } else {
//            $response->setContent('FALSE');
//        }
        var_dump($currentUser);die();
        return $followed;
    }
}

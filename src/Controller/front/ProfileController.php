<?php

namespace App\Controller\front;

use App\Entity\User;
use App\Entity\Relationship;
use App\Entity\Recipe;
use App\Entity\Favorite;
use App\Entity\RecipeRepost;
use App\Repository\UserRepository;
use App\Repository\RelationshipRepository;
use App\Repository\RecipeRepository;
use App\Repository\FavoriteRepository;
use App\Form\ProfileType;
use App\Services\NotificationService;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
            $followBtn = ['title'=>'Ne plus suivre','path'=>'app_front_profile_unfollow'];
        } else {
            $followBtn = ['title'=>'Suivre','path'=>'app_front_profile_follow'];
        }

        $repost = $em->getRepository(RecipeRepost::class)->findBy(['reporter' => $user]);
        
        return $this->render('front/profile/index.html.twig', ['user' => $user , 'follow' => $followBtn, 'repost' => $repost]);
    }

    /**
     * @Route("/{username}/follow", name="profile_follow", methods="GET")
     * @param User $user
     * @return Response
     */
    public function follow(User $user, Request $request ,NotificationService $notificationService): Response
    {

        $relation = new Relationship();
        $relation->setFollowed($user);
        $relation->setFollower($this->getUser());

        $em = $this->getDoctrine()->getManager();

        $submittedToken = $request->get('csrf_token');

        if ($this->isCsrfTokenValid('follow', $submittedToken))
        {
            if ($user->getId() !== $this->getUser()->getId()){

                $message = 'à commencer à vous suivre';
                $url = $this->generateUrl('app_front_profile_show',['username' => $this->getUser()->getUsername()]);
                $notificationService->sendNotification($user,$message,'FOLLOW',$url);

                $em->persist($relation);
                $em->flush();
            }
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{username}/unfollow", name="profile_unfollow", methods="GET")
     * @param User $user
     * @return Response
     */
    public function unfollow(User $user, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $follower = $this->getUser();
        $relation = $em->getRepository(Relationship::class)->findOneBy(['follower'=>$follower->getId(),'followed'=>$user->getId()]);

        $submittedToken = $request->get('csrf_token');

        if ($this->isCsrfTokenValid('follow', $submittedToken))
        {
            $em->remove($relation);
            $em->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}/edit", name="profile_edit", methods="GET|POST")
     */

    public function edit(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('edit', $user);
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_front_profile_show', ['username' => $user->getUsername()]);
        }

        return $this->render('front/profile/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="profile_delete", methods="DELETE")
     */
    public function delete(Request $request, TokenStorageInterface $tokenStorage): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$this->getUser()->getId(), $request->request->get('_token'))) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($this->getUser());
        $tokenStorage->setToken(null);
        $request->getSession()->invalidate();
        $em->flush();

        return $this->redirectToRoute('app_front_auth_login');
    }

    /**
     * @Route("/{username}/favorite", name="favorite_show", methods="GET")
     */
    public function indexFavorite( FavoriteRepository $favoriteRepository, User $user): Response
    {
        $this->denyAccessUnlessGranted('edit', $user);
        $recipes = $favoriteRepository->findFavoritesByUser($this->getUser());

        return $this->render('front/profile/favorite.html.twig', ['recipes' => $recipes, 'user' => $user]);
    }
    /**
     * @Route("/{username}/showFollow", name="profile_show_follow", methods="GET")
     */
    public function showFollow(User $user, Request $request): Response
    {
        $follower = $request->get('follower');
        return $this->render('front/profile/follow.html.twig', ['user' => $user, 'follow' => $follower]);
    }

}

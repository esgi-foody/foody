<?php

namespace App\Controller\front;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\UserRepository;
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
        return $this->render('front/profile/index.html.twig', ['user' => $user]);
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
            'category' => $user,
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

        return $this->redirectToRoute('app_front_security_login');
    }

}

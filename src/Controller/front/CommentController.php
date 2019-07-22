<?php

namespace App\Controller\front;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Services\NotificationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/comment", name="app_front_")
 */

class CommentController extends AbstractController
{
    /**
     * @Route("/new", name="new_comment", methods="POST")
     */
    public function new(Request $request, NotificationService $notificationService): Response
    {

        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository(Recipe::class)->findOneBy(['id' => $request->get('idRecipe')]);

        $comment = new Comment();
        $comment->setRecipe($recipe);
        $comment->setCommentator($this->getUser());
        $comment->setData($request->get('comment')['data']);

        $message = 'à commenté votre recette : ' . $recipe->getTitle();
        $url = $this->generateUrl('recipe_show', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()]);
        $notificationService->sendNotification($recipe->getUserRecipe(), $message, 'COMMENT', $url);

        $em->persist($comment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * @Route("/{id}/uncomment", name="recipe_uncomment", methods="DELETE")
     */
    public function delete(Request $request, Comment $comment): Response
    {

        $this->denyAccessUnlessGranted('delete', $comment);

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }

        return $this->redirect($request->headers->get('referer'));

    }

}

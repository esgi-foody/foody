<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recipe;

class FeedController extends AbstractController
{
    /**
     * @Route("/feed", name="feed")
     */
    public function index()
    {

        $recipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAll();

        if (!$recipes) {
            throw $this->createNotFoundException(
                'Aucune recette trouvée :('
            );
        }

        return $this->render('feed/index.html.twig', [
            'controller_name' => 'FeedController', 'recipes' => $recipes
        ]);


    }
}

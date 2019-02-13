<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        if ($this->getUser() != null){
            $relations = $this->getUser()->getFolloweds();
            $recipes = [];

            foreach ($relations as $relation){

                $userRecipes = $relation->getFollowed()->getRecipes();

                foreach ($userRecipes as $userRecipe){
                    $recipes[] = $userRecipe ;

                }
            }

            usort($recipes, function($a, $b) {
                return strtotime($a->getUpdatedAt()->format('Y-m-d H:i:s')) - strtotime($b->getUpdatedAt()->format('Y-m-d H:i:s'));
            });


            if (!$recipes) {
                throw $this->createNotFoundException(
                    'Aucune recette trouvée :('
                );
            }
            return $this->render('front/home/index.html.twig', [
                'controller_name' => 'HomeController', 'recipes' => $recipes
            ]);
        } else {
            return $this->redirectToRoute('app_front_auth_login');
        }

    }
}

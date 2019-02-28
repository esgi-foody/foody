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
            $relations = $this->getUser()->getFolloweds();
            $recipes = [];

            foreach ($relations as $relation){

                $userRecipes = $relation->getFollowed()->getRecipes();

                foreach ($userRecipes as $userRecipe){
                    $recipes[] = $userRecipe ;
                }
            }

            $recipes = array_slice($recipes, 0, 50);
            $likes = $this->getUser()->getLikes();
            $arrayLikesId = array();
            foreach($likes as $like){
                $arrayLikesId[$like->getRecipe()->getId()]= $like->getId();
            }

            $comments = $this->getUser()->getComments();
            $arrayCommentsId = array();
            foreach ($comments as $comment){
                $arrayCommentsId = [$comment->getRecipe()->getId()];
            }

            usort($recipes, function($a, $b) {
                return strtotime($a->getUpdatedAt()->format('Y-m-d H:i:s')) - strtotime($b->getUpdatedAt()->format('Y-m-d H:i:s'));
            });


            return $this->render('front/home/index.html.twig', [
                'controller_name' => 'HomeController', 'recipes' => $recipes, 'likes_id' => $arrayLikesId, 'commented_recipes' => $arrayCommentsId]);


    }
}

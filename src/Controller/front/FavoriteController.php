<?php

namespace App\Controller\front;

use App\Entity\User;
use App\Entity\Favorite;
use App\Entity\Recipe;
use App\Repository\FavoriteRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use App\Repository\RelationshipRepository;
use App\Form\ProfileType;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/profile", name="app_front_")
 */

class FavoriteController extends AbstractController
{
    /**
     * @Route("/{username}/favorite", name="favorite_show", methods="GET")
     */
    public function index( FavoriteRepository $favoriteRepository, RecipeRepository $recipeRepository): Response
    {
        $recipes=[];
        $favorites = $favoriteRepository->findBy(['userFavorite' => $this->getUser()]);
        $user = $this->getUser();
        foreach ($favorites as $favorite){
            $recipes[]=$recipeRepository->find($favorite->getRecipe());
        }

        if (!$recipes) {
            throw $this->createNotFoundException(
                'Aucune recette trouvée :('
            );
        }
//        dump($recipes);die();

        return $this->render('front/favorite/index.html.twig', ['recipes' => $recipes , 'user' => $user]);
    }

}
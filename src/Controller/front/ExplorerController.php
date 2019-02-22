<?php

namespace App\Controller\front;

use App\Form\ExplorerType;
use App\Repository\UserRepository;
use App\Services\ExplorerFilters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecipeRepository;

/**
 * Class ExplorerController
 * @package App\Controller
 * @Route("/explorer", name="app_front_")
 */
class ExplorerController extends AbstractController
{
    /**
     * @Route("/", name="explorer_index", methods={"GET", "POST"})
     */
    public function index(Request $request, UserRepository $userRepository, RecipeRepository $recipeRepository)
    {

        $data = ['query' => null, 'category' => null, 'calorie_min' => null, 'calorie_max' => null];
        $results = ['users' => null, 'recipes' => null];
        $form = $this->createForm(ExplorerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $users = $userRepository->findByUsername($data['query']);
            $recipes = $recipeRepository->findWithFilters($data);
            $results = ['users' => $users, 'recipes' => $recipes];
        } else {
            $results['recipes'] = $recipeRepository->findByUserSuggestion($this->getUser()->getId(),'21');;
        }

        return $this->render('front/explorer/index.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
            'results' => $results,
        ]);
    }
}

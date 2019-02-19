<?php

namespace App\Controller\front;

use App\Form\ExplorerType;
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
     * @Route("/", name="search_index", methods={"GET", "POST"})
     */
    public function index(Request $request, ExplorerFilters $explorerFilters,RecipeRepository $recipeRepository)
    {

        $data = ['query' => null];
        $results = ['users' => null, 'recipes' => null, 'categories' => null];
        $form = $this->createForm(ExplorerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $results = $explorerFilters->filters($data);
        } else {
            $results ['recipes'] = $recipeRepository->findByUserSuggestion($this->getUser()->getId(),'21');;
        }

        return $this->render('front/explorer/index.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
            'results' => $results,
        ]);
    }
}

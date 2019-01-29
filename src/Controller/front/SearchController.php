<?php

namespace App\Controller\front;

use App\Entity\Category;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 * @Route("/search", name="app_front_")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="search_index", methods={"GET", "POST"})
     */
    public function index(Request $request, UserRepository $userRepository, RecipeRepository $recipeRepository)
    {
        $form = $this->createFormBuilder(null)
            ->add('query', TextType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'label'        => false,
                'class'        => Category::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'required'     => false,
            ])
            ->getForm()
        ;

        $users = null;
        $recipes = null;
        $categories = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            switch ($data['category']->isEmpty()) {
                case true && $data['query']:
                    $users = $userRepository->findByUsername($data['query']);
                    $recipes = $recipeRepository->findByTitle($data['query']);
                    break;
                case false && $data['query']:
                    $recipes = $recipeRepository->findByCategory($data['query'], $data['category']);
                    $categories = $data['category'];
                    break;
            }
        }

        return $this->render('front/search/index.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
            'recipes' => $recipes,
            'categories' => $categories,
        ]);
    }
}

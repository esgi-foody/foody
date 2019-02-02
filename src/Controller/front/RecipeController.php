<?php

namespace App\Controller\front;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\FileUploader;


/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{
    /**
     * @Route("/", name="recipe_index", methods="GET")
     */
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('front/recipe/index.html.twig', ['recipes' => $recipeRepository->findBy(['userRecipe' => $this->getUser()])]);
    }

    /**
     * @Route("/new", name="recipe_new", methods="GET|POST")
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {

        $recipe = new Recipe();
        $recipeStep =  new RecipeStep();
        $ingredient = new Ingredient();
        $recipe->addRecipeStep($recipeStep);
        $recipe->addIngredient($ingredient);

        $recipe->setUserRecipe($this->getUser());
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $fileName = $fileUploader->upload($recipe->getPathCoverImg(),'recipes');
            $recipe->setPathCoverImg($fileName);

            foreach ($recipe->getRecipeSteps() as $recipeStep) {
                $recipeStep->setRecipe($recipe);
                $recipe->addRecipeStep($recipeStep);
            }

            foreach ($recipe->getIngredients() as $ingredient) {
                $ingredient->setRecipe($recipe);
                $recipe->addIngredient($ingredient);
            }

            foreach ($recipe->getCategories() as $category) {
                $category->addRecipe($recipe);
                $recipe->addCategory($category);
            }

            $calories = $recipe->getCarbohydrate()* 4 + $recipe->getProtein() * 4 + $recipe->getFat()*9;
            $recipe->setCalory($calories);

            $em = $this->getDoctrine()->getManager();
            $recipe->setUserRecipe($this->getUser());
            $em->persist($recipe);
            $em->flush();



            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('front/recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}-{slug}", name="recipe_show", methods="GET")
     */
    public function show(Recipe $recipe): Response
    {
        $iterator =  $recipe->getRecipeSteps()->getIterator();

        $iterator->uasort(function ($a, $b) {
            return ($a->getStepNumber() < $b->getStepNumber()) ? -1 : 1;
        });
        $recipe->setRecipeSteps(new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($iterator))) ;

        return $this->render('front/recipe/show.html.twig', ['recipe' => $recipe]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_edit", methods="GET|POST")
     */
    public function edit(Request $request, Recipe $recipe, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($recipe->getRecipeSteps() as $recipeStep) {
                $recipeStep->setRecipe($recipe);
                $recipe->addRecipeStep($recipeStep);
            }

            foreach ($recipe->getIngredients() as $ingredient) {
                $ingredient->setRecipe($recipe);
                $recipe->addIngredient($ingredient);
            }

            $fileName = $fileUploader->upload($recipe->getPathCoverImg(),'recipes');
            $recipe->setPathCoverImg($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recipe_index', ['id' => $recipe->getId()]);
        }

        return $this->render('front/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_delete", methods="DELETE")
     */
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recipe);
            $em->flush();
        }

        return $this->redirectToRoute('recipe_index');
    }
}

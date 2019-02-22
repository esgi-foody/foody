<?php

namespace App\Controller\front;

use App\Entity\Favorite;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Entity\Like;
use App\Entity\RecipeRepost;
use App\Entity\User;
use App\Form\RecipeType;
use App\Repository\FavoriteRepository;
use App\Services\NotificationService;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request): Response
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

            $recipe = $this->calculateMacro($recipe);

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

        $em = $this->getDoctrine()->getManager();
        $liked = $em->getRepository(Like::class)->findOneBy(['liker' => $this->getUser(),'recipe' => $recipe]);
        $favorite = $em->getRepository(Favorite::class)->findOneBy(['userFavorite' => $this->getUser(),'recipe' => $recipe]);
        $reposted = $em->getRepository(RecipeRepost::class)->findOneBy(['reporter' => $this->getUser(),'recipe' => $recipe]);

        return $this->render('front/recipe/show.html.twig', ['recipe' => $recipe ,'liked' => $liked, 'favorite' => $favorite, 'reposted' => $reposted]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_edit", methods="GET|POST")
     */
    public function edit(Request $request, Recipe $recipe): Response
    {
        $this->denyAccessUnlessGranted('edit', $recipe);

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


            $recipe = $this->calculateMacro($recipe);

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
        $this->denyAccessUnlessGranted('delete', $recipe);

        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recipe);
            $em->flush();
        }

        return $this->redirectToRoute('recipe_index');
    }


    /**
     * @Route("/{id}/like", name="recipe_like", methods="GET")
     */
    public function like(Request $request, Recipe $recipe,NotificationService $notificationService): Response
    {

        if ($this->isCsrfTokenValid('like'.$recipe->getId(),$request->query->get('csrf_token'))) {

            $like = new Like();

            $like->setLiker($this->getUser());
            $like->setRecipe($recipe);
            $recipe->getLikes()->add($like);

            $em = $this->getDoctrine()->getManager();

            $message = 'à aimé votre recette : '.$recipe->getTitle();
            $url = $this->generateUrl('recipe_show',['id' => $recipe->getId(),'slug'=>$recipe->getSlug()]);
            $notificationService->sendNotification($recipe->getUserRecipe() ,$message,'LIKE',$url);
            $em->flush();
        }

        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId(),'slug' => $recipe->getSlug()]);
    }



    /**
     * @Route("/{id}/{idLike}/unlike", name="recipe_unlike", methods="GET")
     */
    public function unlike(Request $request,Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('unlike'.$recipe->getId(),$request->query->get('csrf_token'))) {
            $em = $this->getDoctrine()->getManager();
            $like = $em->getRepository(Like::class)->findOneBy(['id' => $request->get('idLike')]);
            $em->remove($like);
            $em->flush();
        }

        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId(),'slug' => $recipe->getSlug()]);
    }


    /**
     * @Route("/{id}/favorite", name="recipe_favorite", methods="GET")
     */
    public function favorite(Recipe $recipe, Request $request): Response
    {

        $submittedToken = $request->get('csrf_token');

        if ($this->isCsrfTokenValid('favorite', $submittedToken))
        {
            $favorite = new Favorite();

            $favorite->setUserFavorite($this->getUser());
            $favorite->setRecipe($recipe);

            $em = $this->getDoctrine()->getManager();
            $em->persist($favorite);
            $em->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }



    /**
     * @Route("/{id}/unfavorite", name="recipe_unfavorite", methods="GET")
     */
    public function unfavorite(Request $request,Recipe $recipe): Response
    {

        $submittedToken = $request->get('csrf_token');

        if ($this->isCsrfTokenValid('unfavorite', $submittedToken))
        {
            $em = $this->getDoctrine()->getManager();
            $favorite = $em->getRepository(Favorite::class)->findOneBy(['userFavorite' => $this->getUser(), 'recipe' =>$recipe->getId()]);
            $em->remove($favorite);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }

    private function calculateMacro(Recipe $recipe): Recipe
    {
        $protein = 0;
        $carbohydrate = 0;
        $fat = 0;

        foreach ($recipe->getIngredients() as $ingredient) {
            $quantity = $ingredient->getMeasuringUnit() === 'piece'? $ingredient->getQuantity() : $ingredient->getQuantity() /100 ;
            $carbohydrate+= $ingredient->getCarbohydrate() * $quantity;
            $protein+= $ingredient->getProtein() * $quantity ;
            $fat+= $ingredient->getFat() * $quantity ;
        }

        $calories = $carbohydrate * 4 + $protein * 4 + $fat * 9;
        $recipe->setCarbohydrate($carbohydrate);
        $recipe->setProtein($protein);
        $recipe->setFat($fat);
        $recipe->setCalory($calories);

        return $recipe;
    }

    /**
     * @Route("/{id}/repost", name="recipe_repost", methods="GET")
     */
    public function recipeRepost(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('repost'.$recipe->getId(), $request->query->get('csrf_token'))) {

            $repost = new RecipeRepost();
            $repost->setReporter($this->getUser());
            $repost->setRecipe($recipe);

            $em = $this->getDoctrine()->getManager();
            $em->persist($repost);
            $em->flush();
        }

        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId(),'slug' => $recipe->getSlug()]);
    }

    /**
     * @Route("/{id}/{idRepost}/unrepost", name="recipe_unrepost", methods="GET")
     */
    public function unrepost(Request $request,Recipe $recipe): Response
    {

        if ($this->isCsrfTokenValid('unrepost'.$recipe->getId(),$request->query->get('csrf_token'))) {
            $em = $this->getDoctrine()->getManager();
            $repost = $em->getRepository(RecipeRepost::class)->findOneBy(['id' => $request->get('idRepost')]);
            $em->remove($repost);
            $em->flush();
        }

        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId(),'slug' => $recipe->getSlug()]);
    }

    /**
     * @Route("/{id}", name="recipe_repost_show", methods="GET")
     */
    public function showRecipeRepost(Recipe $recipe): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reposted = $em->getRepository(RecipeRepost::class)->findBy(['reporter' => $this->getUser()]);
        dump($reposted);die;

        return $this->render('front/recipe/show.html.twig', ['recipe' => $recipe ,'liked' => $liked, 'reposted' => $reposted]);
    }
}

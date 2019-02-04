<?php

namespace App\Services;



use App\Repository\RecipeRepository;
use App\Repository\UserRepository;

class ExplorerFilters
{
    private $userRepository;

    private $recipeRepository;

    /**
     * ExplorerFilters constructor.
     * @param UserRepository $userRepository
     * @param RecipeRepository $recipeRepository
     */
    public function __construct(UserRepository $userRepository, RecipeRepository $recipeRepository)
    {
        $this->userRepository = $userRepository;
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * @param $data
     * @return array
     */
    public function filters($data)
    {
        $users = null;
        $recipes = null;
        $categories = null;

        if ($data['category']->isEmpty() && $data['query']) {
            $users = $this->userRepository->findByUsername($data['query']);
            $recipes = $this->recipeRepository->findByTitle($data['query']);
        } else if (!$data['category']->isEmpty() && $data['query']) {
            $recipes = $this->recipeRepository->findByCategory($data['query'], $data['category']);
            $categories = $data['category'];
        }

        return ['users' => $users, 'recipes' => $recipes, 'categories' => $categories];
    }
}
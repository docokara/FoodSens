<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(RecipeRepository $recipes,UserInterface $user = null): Response
    { 
        $favs = null;
        if($this->getUser()) $favs = $this->getUser()->getFavories();
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'name' => 'allRecipe'
        ]);
    }
    
     /**
     * @Route("/showRecipe/{id}", name="app_showRecipe")
     */
    public function showRecipe(Request $request,Recipe $recipe) : Response
    {
        return $this->render('home/index.html.twig', [
            'recipe' => $recipe,
            'name' => 'showRecipe'
        ]); 
    }

}

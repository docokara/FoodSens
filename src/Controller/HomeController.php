<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Boolean;
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
            'controller_name' => 'HomeController',
            'recipes' => $recipes->findAll(),
            'favs' => $favs,
            'name' => 'allRecipe'
        ]);
    }
    /**
     * @Route("/isLiked/{id}", name="app_isLiked")
     */
    public function isLiked($id,Recipe $recipe,Request $request,UserInterface $user = null): Boolean
    {
        if (!$this->getUser()) return false;
        $favs = $this->getUser()->getFavories();
        foreach($favs as $fav){
            if($fav == $id) return true;
        }
        return false;
    }

     /**
     * @Route("/like/{id}", name="app_like")
     */
    public function fav(Request $request,UserRepository $users ,RecipeRepository $recipes,Recipe $recipe,UserInterface $user = null): Response
    { 
        $favs = null;
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $favs = $this->getUser()->getFavories();
        dump($favs[0]);
        $isLiking = true;
        $fav = $recipe->getFav();
        foreach($fav as $id){
            if($id == $user){ 
            $isLiking = false;
            }
        }
        $recipe->addFav($user); 
        if(!$isLiking) $recipe->removeFav($user);
        $recipes->add($recipe,true);
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'favs' => $favs,
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

<?php

namespace App\Controller;

use App\Entity\Fridge;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\FridgeType;
use App\Form\RecipeType;
use App\Repository\FridgeRepository;
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
    /**
     * @Route("/myFav/{id}", name="app_myFav")
     */
    public function myFav(Request $request,User $user) : Response
    {
        
        return $this->render('home/index.html.twig', [
            'recipes' => $user->getFavories(),
            'name' => 'myFav'
        ]); 
    }

     /**
     * @Route("/myFridge/{id}", name="app_myFridge")
     */
    public function myFridge(Request $request,User $user) : Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $fridge = $this->getUser()->getFridge();
        return $this->render('home/index.html.twig', [
            'ingredients' => $fridge->getIngredients(),
            'name' => 'myFridge'
        ]); 
    }

      /**
     * @Route("/addIngredient/{id}", name="app_addIngredient")
     */
    public function addIngredients($id,Request $request,FridgeRepository $fridges,UserRepository $users,UserInterface $user = null,Fridge $fridge = null) : Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $user = $this->getUser();
        $fridge = $user->getFridge();
        $form = $this->createForm(FridgeType::class, $fridge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFridge($fridge);
            $users->add($user,true);
            return $this->redirectToRoute('app_myFridge', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'ingredients' => $fridge->getIngredients(),
            'name' => 'myFridge'
        ]); 
    }
      /**
     * @Route("/recipe/delete/{id}", name="app_recipe_delete")
     */
    public function deleteRecipe($id,Request $request,FridgeRepository $fridges,UserRepository $users,UserInterface $user = null,Fridge $fridge = null) : Response
    {
    }
      /**
     * @Route("/recipe/create", name="app_recipe_create")
     */
    public function modifyRecipe(Request $request,UserInterface $user = null,Recipe $recipe = null,RecipeRepository $recipes) : Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipes->add($recipe,true);
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'name' => 'modifyRecipe',
            'form' => $form->createView()
        ]); 
    }
}

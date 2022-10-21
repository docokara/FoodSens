<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Form\UserType;
use App\Form\RecipeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Repository\FridgeRepository;
use App\Repository\IngredientCategorieRepository;
use App\Repository\IngredientRepository;
    /**
     * @Route("/admin")
     */
class AdminController extends AbstractController
{
    
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        $isAdmin = false;
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $roles = $this->getUser()->getRoles();
        
        foreach($roles as $role){
            if($role == 'admin') $isAdmin = true;
        }
        
      //  if(!$isAdmin) return $this->redirectToRoute('app_home');
        return $this->render('admin/index.html.twig', [
            "entity" => ["users","recipes","ingredients","ingredientCategories"]
        ]);
    }

    /**
     * @Route("/getAll/{name}", name="admin_getAll")
     */
    public function getAll($name,UserRepository $users,RecipeRepository $recipe,IngredientRepository $ingredient,IngredientCategorieRepository $ingredientCat,FridgeRepository $fridge): Response
    {
        return $this->render('admin/form/index.html.twig', [
            'data' => ${$name}->findAll(),
            'name' => $name
        ]); 
    }
     /**
     * @Route("/create/{name}", name="admin_create")
     */
    public function create($name,Request $request,UserRepository $users,RecipeRepository $recipes,IngredientRepository $ingredients,IngredientCategorieRepository $ingredientCategories): Response
    {
       
        $form = null;
        $element = null;
        if($name == "users") {
            $element = new User();
            $form = $this->createForm(UserType::class, $element);
        }
        if($name == "recipe") {
            $element = new Recipe();
            $form = $this->createForm(RecipeType::class, $element);
        }
        if($name == "ingredients") {
            $element = new Ingredient();
            $form = $this->createForm(IngredientType::class, $element);
        }
        if($name == "ingredientCategories") {
            $element = new IngredientCategorie();
            $form = $this->createForm(IngredientCategorieType::class, $element);
        }
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            ${$name}->add($element, true);

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/form/edit.html.twig', [
            'data' => $element,
            'form' => $form,
        ]);
    }
     /**
     * @Route("/edit/{id}/{name}", name="admin_edit")
     */
    public function edit($name,$id,Request $request,UserRepository $users,User $user,RecipeRepository $recipes,Recipe $recipe,IngredientRepository $ingredients,Ingredient $ingredient,IngredientCategorieRepository $ingredientCategories,IngredientCategorie $ingredientCategorie): Response
    {
        $form = null;
        $element = null;
        if($name == "users") {
            $form = $this->createForm(UserType::class, $user);
            $element = $user;
        }
        if($name == "recipe") {
            $form = $this->createForm(RecipeType::class, $recipe);
            $element = $recipe;
        }
        if($name == "ingredients") {
            $form = $this->createForm(IngredientType::class, $ingredient);
            $element = $ingredient;
        }
        if($name == "ingredientCategories") {
            $form = $this->createForm(IngredientCategorieType::class, $ingredientCategorie);
            $element = $ingredientCategorie;
        }
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            ${$name}->add($element, true);

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/form/edit.html.twig', [
            'data' => $element,
            'form' => $form,
        ]);
    }
     /**
     * @Route("/delete/{id}/{name}", name="admin_delete")
     */
    public function delete($name,$id,Request $request,UserRepository $users,User $user,RecipeRepository $recipes,Recipe $recipe,IngredientRepository $ingredients,Ingredient $ingredient,IngredientCategorieRepository $ingredientCategories,IngredientCategorie $ingredientCategorie): Response
    {
        $element=null;

        if($name == "users") {
            $element = $user;
        }
        if($name == "recipe") {
            $element = $recipe;
        }
        if($name == "ingredients") {
            $element = $ingredient;
        }
        if($name == "ingredientCategories") {
            $element = $ingredientCategorie;
        }

        ${$name}->remove($element,true);

        return $this->render('admin/form/index.html.twig', [
            'data' => ${$name}->findAll()
        ]);
    }
}

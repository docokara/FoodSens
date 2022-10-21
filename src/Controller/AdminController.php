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
            dump($role == 'admin');
            if($role == 'admin') $isAdmin = true;
        }
        
        if(!$isAdmin) return $this->redirectToRoute('app_home');
        return $this->render('admin/index.html.twig', [
            "entity" => ["users","recipe","ingredient","ingredientCategorie","fridge"]
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
     * @Route("/create", name="admin_create")
     */
    public function create(Request $request,UserRepository $users) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $users->add($user, true);

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/form/edit.html.twig', [
            'data' => $user,
            'form' => $form,
        ]);
    }
     /**
     * @Route("/edit/{id}/{name}", name="admin_edit")
     */
    public function edit($name,$id,Request $request,UserRepository $users,User $user,RecipeRepository $recipes,Recipe $recipe,IngredientRepository $ingredient,IngredientCategorieRepository $ingredientCat,FridgeRepository $fridge): Response
    {
        $form = null;
        if($name == "users") $el = $this->createForm(UserType::class, $user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $users->add($user, true);

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/form/edit.html.twig', [
            'data' => $user,
            'form' => $form,
        ]);
    }
     /**
     * @Route("/delete/{id}", name="admin_delete")
     */
    public function delete(UserRepository $users,User $user): Response
    {
        $users->remove($user,true);
        return $this->render('admin/form/index.html.twig', [
            'data' => $users->findAll(),
        ]);
    }
}

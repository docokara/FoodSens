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
          "paths" => ['user','recipe']
        ]);
    }
    /**
     * @Route("/user", name="admin_user")
     */ 
    public function user(UserRepository $users): Response
    {
        return $this->render('admin/form/index.html.twig', [
            'data' => $users->findAll(),
            'paths' => ["admin_user_edit","admin_user_delete"],
            'name' => "user"

        ]);
    }
     /**
     * @Route("user/create", name="admin_user_create")
     */
    public function createUser(Request $request,UserRepository $users) : Response
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
            'name' => 'user',
            'type' => 'create'
        ]);
    }
     /**
     * @Route("user/edit/{id}", name="admin_user_edit")
     */
    public function editUser(Request $request,UserRepository $users,User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $users->add($user, true);

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/form/edit.html.twig', [
            'data' => $user,
            'form' => $form,
            'name' => 'user',
            'type' => 'edit'

        ]);
    }
     /**
     * @Route("user/delete/{id}", name="admin_user_delete")
     */
    public function deleteUser(UserRepository $users,User $user): Response
    {
        $users->remove($user,true);
        return $this->render('admin/form/index.html.twig', [
            'data' => $users->findAll(),
            'paths' => ["admin_user_edit","admin_user_delete"]
        ]);
    }
        /**
     * @Route("/recipe", name="admin_recipe")
     */ 
    public function recipe(RecipeRepository $recipes): Response
    {
        dump($recipes->findAll());
        return $this->render('admin/form/index.html.twig', [
            'data' => $recipes->findAll(),
            'paths' => ["admin_recipe_edit","admin_recipe_delete"],
            'name' => "recipe"
        ]);
    }
     /**
     * @Route("recipe/edit/{id}", name="admin_recipe_edit")
     */

       /**
     * @Route("recipe/create", name="admin_recipe_create")
     */
    public function createRecipe(Request $request,RecipeRepository $recipes) : Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $recipes->add($recipe, true);

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/form/edit.html.twig', [
            'form' => $form,
            'name' => 'user',
            'type' => 'create'
        ]);
    }
    /**
     * @Route("recipe/edit/{id}", name="admin_recipe_edit")
     */
    public function editRecipe(Request $request,RecipeRepository $recipes,Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $recipes->add($recipe, true);

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/form/edit.html.twig', [
            'data' => $recipe,
            'form' => $form,
            'name' => 'user',
            'type' => 'edit'
        ]);
    }
     /**
     * @Route("recipe/delete/{id}", name="admin_recipe_delete")
     */
    public function deleteRecipe(RecipeRepository $recipes,Recipe $recipe): Response
    {
        $recipes->remove($recipe,true);
        return $this->render('admin/form/index.html.twig', [
            'data' => $recipes->findAll(),
            'paths' => ["admin_recipe_edit","admin_recipe_delete"]
        ]);
    }
}

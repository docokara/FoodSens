<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Form\RecipeType;

use App\Repository\FridgeRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\IngredientCategorieRepository;
use App\Entity\IngredientCategorie;
use App\Form\IngredientCategorieType;
use App\Repository\IngredientRepository;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use Doctrine\ORM\EntityManagerInterface ;
use App\Service\FileUploader;
/**
 * @Route("/admin"), IsGranted('USER_ADMIN')
 *
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
    public function getAll($name,UserRepository $users,RecipeRepository $recipes,IngredientRepository $ingredients,IngredientCategorieRepository $ingredientCategories): Response
    {
        return $this->render('admin/form/index.html.twig', [
            'data' => ${$name}->findAll(),
            'name' => $name
        ]); 
    }
     /**
     * @Route("/{name}/{id}", name="admin_update")
     */
    public function update($name,$id,Request $request,UserRepository $users,User $user = null,Recipe $recipe = null,RecipeRepository $recipes,IngredientRepository $ingredients,Ingredient $ingredient = null,IngredientCategorieRepository $ingredientCategories,IngredientCategorie $ingredientCategorie = null,FileUploader $fileUploader): Response
    {
        $form = null;
        $element = null;
        if($name == "users") {
            $element = $id != "UNDEFINED" ? $user : new User();
            $form = $this->createForm(UserType::class, $element);
        }
        if($name == "recipes") {
            $element = $id != "UNDEFINED" ? $recipe : new Recipe();
            $form = $this->createForm(RecipeType::class, $element);
        }
        if($name == "ingredients") {
            $element = $id != "UNDEFINED" ? $ingredient : new Ingredient();
            $form = $this->createForm(IngredientType::class, $element);
        }
        if($name == "ingredientCategories") {
            $element = $id  != "UNDEFINED" ? $ingredientCategorie : new IngredientCategorie();
            $form = $this->createForm(IngredientCategorieType::class, $element);
        }
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {

          if($name == "recipes" | $name == "ingredients"){ 
            $file = $form->get('photo')->getData();
            if ($file) {
                $FileName = $fileUploader->upload($file);
                dump($FileName);
                $element->setImage($FileName);
            }
        }
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
    public function delete($name,Request $request,UserRepository $users,User $user = null,Recipe $recipe = null,RecipeRepository $recipes,IngredientRepository $ingredients,Ingredient $ingredient = null,IngredientCategorieRepository $ingredientCategories,IngredientCategorie $ingredientCategorie = null): Response
    {
        $element=null;
        if($name == "users") {
            $element = $user;
        }
        if($name == "recipes") {
            dump("rr");
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
            'data' => ${$name}->findAll(),
            'name' => $name
        ]);
    }
}

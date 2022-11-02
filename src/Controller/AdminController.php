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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
        return $this->render('home/index.html.twig', [
            "entity" => ["users","recipes","ingredients","ingredientCategories"],
            "page_name" => "admin_page"
        ]);
    }

    /**
     * @Route("/getAll/{name}", name="admin_getAll")
     */
    public function getAll($name,UserRepository $users,RecipeRepository $recipes,IngredientRepository $ingredients,IngredientCategorieRepository $ingredientCategories): Response
    {
        return $this->render('home/index.html.twig', [
            'data' => ${$name}->findAll(),
            'name' => $name,
            "page_name" => "admin_page_getAll"
        ]); 
    }
     /**
     * @Route("/edit/{name}/{id}", name="admin_update")
     */
    public function update($name,$id = 'undefined',Request $request,UserRepository $users,User $user = null,Recipe $recipe = null,RecipeRepository $recipes,IngredientRepository $ingredients,Ingredient $ingredient = null,IngredientCategorieRepository $ingredientCategories,IngredientCategorie $ingredientCategorie = null,FileUploader $fileUploader,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = null;
        $element = null;
        if($name == "users") {
            $element = $id != 'undefined' ? $user : new User();
            $form = $this->createForm(UserType::class, $element);
            $form->remove('oldpassword');
        }
        if($name == "recipes") {
            $element = $id != 'undefined' ? $recipe : new Recipe();
            $form = $this->createForm(RecipeType::class, $element);
        }
        if($name == "ingredients") {
            $element = $id != 'undefined' ? $ingredient : new Ingredient();
            $form = $this->createForm(IngredientType::class, $element);
        }
        if($name == "ingredientCategories") {
            $element = $id  != 'undefined' ? $ingredientCategorie : new IngredientCategorie();
            $form = $this->createForm(IngredientCategorieType::class, $element);
        }
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            if($name == "users") {
                $element->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('password')->getData()
                        )
                    );
            } 
          if($name == "recipes" | $name == "ingredients"){ 
            $file = $form->get('photo')->getData();
            if ($file) {
                $FileName = $fileUploader->upload($file);
                dump($FileName);
                $element->setImage($FileName);
            }
        }
            ${$name}->add($element, true);

            return $this->redirectToRoute('admin_getAll', ['name' => $name], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/index.html.twig', [
            'data' => $element,
            'name' => $name,
            'form' => $form,
            'page_name' => 'admin_page_upate'
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

        return $this->render('home/index.html.twig', [
            'data' => ${$name}->findAll(),
            'name' => $name,
            'page_name' => 'admin_page_getAll'
        ]);
    }
}

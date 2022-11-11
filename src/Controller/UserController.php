<?php

namespace App\Controller;

use App\Entity\Fridge;
use App\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use App\Repository\RecipeRepository;
use App\Entity\Recipe;
use App\Form\RecipeType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FridgeType;
use App\Repository\CommentairesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\FridgeRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Service\FileUploader;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('searchRecipe');


        return $this->render('index.html.twig', [
            'info' => $this->getUser(),
            'page_name' => 'userProfile'
        ]);
    }
    /**
     * @Route("/edit/{id}/{onModify}", name="user_profil_edit")
     */
    public function editProfil(Request $request, UserRepository $users, User $user, $onModify, UserPasswordHasherInterface $userPasswordHasher, FileUploader $fileUploader): Response
    {

        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        if ($onModify == "pseudo") {
            $form = $this->createForm(UserType::class, $user);
            $form->remove('roles');
            $form->remove('isVerified');
            $form->remove('email');
            $form->remove('password');
            $form->remove('photo');
            $form->remove('oldpassword');
        }
        if ($onModify == "email") {
            $form = $this->createForm(UserType::class, $user);
            $form->remove('roles');
            $form->remove('isVerified');
            $form->remove('pseudo');
            $form->remove('password');
            $form->remove('photo');
            $form->remove('oldpassword');
        }
        if ($onModify == "password") {
            $form = $this->createForm(UserType::class, $user);
            $form->remove('roles');
            $form->remove('isVerified');
            $form->remove('email');
            $form->remove('pseudo');
            $form->remove('photo');
        }
        if ($onModify == "profilePicture") {
            $form = $this->createForm(UserType::class, $user);
            //$form->add('image', FileType::class); 
            $form->remove('roles');
            $form->remove('isVerified');
            $form->remove('email');
            $form->remove('pseudo');
            $form->remove('oldpassword');
            $form->remove('password');
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($onModify == "password") {
                $newpwd = $form->get('password')->getData();
                $newEncodedPassword = $userPasswordHasher->hashPassword($user, $newpwd);

                $oldPsw = $form->get('oldpassword')->getData();
                $oldEncodePsw = $userPasswordHasher->hashPassword($user, $oldPsw);
                $user->setPassword($newEncodedPassword);
                if ($oldEncodePsw == $userPasswordHasher->hashPassword($user, $user->getPassword())) {
                    // $user->setPassword($newEncodedPassword);

                } else {
                    //return une error
                }
            }


            if ($onModify == "profilePicture") {
                $file = $form->get('photo')->getData();
                if ($file) {
                    $FileName = $fileUploader->upload($file);

                    $user->setImage($FileName);
                }
            }

            $users->add($user, true);
            return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('index.html.twig', [
            'data' => $user,
            'page_name' => 'userProfileEdition',
            'form' => $form,
            'onModify' => $onModify
        ]);
    }

    /**
     * @Route("/myFav", name="user_favories")
     */

    public function myFav(Request $request): Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        return $this->render('index.html.twig', [
            'recipes' => $this->getUser()->getFavories(),
            'page_name' => 'myFav'
        ]);
    }

    /**
     * @Route("/myFridge", name="user_fridge")
     */
    public function myFridge(Request $request): Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $fridge = $this->getUser()->getFridge();
        return $this->render('index.html.twig', [
            'ingredients' => $fridge->getIngredients(),
            'page_name' => 'myFridge'
        ]);
    }


    /**
     * @Route("/myFridge/deleteIngredient/{id}", name="user_fridge_deleteIngredient")
     */
    public function deleteFridgeIngredient(Request $request, Ingredient $ingredient, UserRepository $users): Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        if ($this->getUser()->getFridge()->getIngredients() != null) {
            $isInside = false;
            foreach ($this->getUser()->getFridge()->getIngredients() as $element) {
                dump($element->getId() == $ingredient->getId());
                if ($element->getId() == $ingredient->getId()) $isInside = true;
            }
            if ($isInside) {
                $this->getUser()->getFridge()->removeIngredient($ingredient);
                $users->add($this->getUser(), true);
            }
        }

        return $this->redirectToRoute('user_fridge', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/myFridge/addIngredient", name="user_fridge_addIngredient")
     */
    public function addFridgeIngredients(Request $request, FridgeRepository $fridges, UserRepository $users, Fridge $fridge = null): Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $user = $this->getUser();
        $fridge = $user->getFridge();
        $form = $this->createForm(FridgeType::class, $fridge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFridge($fridge);
            $users->add($user, true);
            return $this->redirectToRoute('user_fridge', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('index.html.twig', [
            'form' => $form->createView(),
            'ingredients' => $fridge->getIngredients(),
            'page_name' => 'myFridge'
        ]);
    }
    /**
     * @Route("/recipe/delete/{id}", name="user_recipe_delete")
     */
    public function deleteRecipe($id, Request $request, UserRepository $users, UserInterface $user = null, Recipe $recipe, RecipeRepository $recipes): Response
    {
        $user = $this->getUser();
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        if ($user != $recipe->getAuthor() && (!in_array("ROLE_ADMIN", $user->getRoles()))) return $this->redirectToRoute('app_home');
        $recipes->remove($recipe, true);

        return $this->render('index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'allRecipe'
        ]);
    }

    /**
     * @Route("/recipe/edit/{id}", name="user_recipe_edit")
     */
    public function editRecipe($id, Request $request, UserInterface $user = null, Recipe $recipe, RecipeRepository $recipes, FileUploader $fileUploader): Response
    {
        $user = $this->getUser();
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        if ($user != $recipe->getAuthor() && (!in_array("ROLE_ADMIN", $user->getRoles()))) return $this->redirectToRoute('app_home');
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->remove("Author");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();
            if ($file) {
                $FileName = $fileUploader->upload($file);
                dump($FileName);
                $recipe->setImage($FileName);
            }

            $recipes->add($recipe, true);
            return $this->redirectToRoute('show_recipe', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->render('index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'updateRecipe',
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/recipe/create", name="user_recipe_create")
     */
    public function createRecipe(Request $request, UserInterface $user = null, Recipe $recipe = null, RecipeRepository $recipes, FileUploader $fileUploader): Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->remove("Author");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();
            if ($file) {
                $FileName = $fileUploader->upload($file);
                dump($FileName);
                $recipe->setImage($FileName);
            }
            $recipe->setAuthor($this->getUser());
            $recipes->add($recipe, true);
            return $this->redirectToRoute('searchRecipe', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'updateRecipe',
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/like/{id}", name="user_like")
     */
    public function fav(Request $request, UserRepository $users, RecipeRepository $recipes, Recipe $recipe, UserInterface $user = null): Response
    {
        $favs = null;
        $isLiking = true;

        if (!$this->getUser()) return $this->redirectToRoute('app_home');

        $favs = $this->getUser()->getFavories();
        $fav = $recipe->getFav();
        foreach ($fav as $id) {
            if ($id == $user) {
                $isLiking = false;
            }
        }
        if (!$isLiking) $recipe->removeFav($user);
        else $recipe->addFav($user);
        $recipes->add($recipe, true);
        return $this->redirectToRoute('user_favories', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/user/delete/self", name="user_delete_self")
     */
    public function userDeleteSelf(Request $request, UserRepository $users, CommentairesRepository $commentaires, RecipeRepository $recipes): Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        foreach ($this->getUser()->getCommentaires() as $el) {
            $commentaires->remove($el, true);
        }
        foreach ($this->getUser()->getRecipes() as $recipe) {
            $recipes->remove($recipe, true);
        }
        $users->remove($this->getUser(), true);

        return $this->redirectToRoute('app_logout');
    }
}

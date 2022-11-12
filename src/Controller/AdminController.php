<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Form\RecipeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\IngredientCategorieRepository;
use App\Entity\IngredientCategorie;
use App\Form\IngredientCategorieType;
use App\Repository\IngredientRepository;
use App\Entity\Ingredient;
use App\Form\CommentairesType;
use App\Form\IngredientType;
use App\Repository\CommentairesRepository;
use App\Service\FileUploader;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/admin")
 *
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="admin",methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            "entity" => ["users", "recipes", "ingredients", "ingredientCategories", "commentaires"],
            "page_name" => "admin_page"
        ]);
    }

    /**
     * @Route("/getAll/{name}", name="admin_getAll",methods={"GET"})
     */
    public function getAll($name, UserRepository $users, RecipeRepository $recipes, IngredientRepository $ingredients, IngredientCategorieRepository $ingredientCategories, CommentairesRepository $commentaires): Response
    {
        return $this->render('index.html.twig', [
            'data' => ${$name}->findAll(),
            'name' => $name,
            "page_name" => "admin_page_getAll"
        ]);
    }
    /**
     * @Route("/edit/{name}/{id}", name="admin_update",methods={"GET","POST"})
     */
    public function update($name, $id = 'undefined', Request $request, UserRepository $users, User $user = null, Recipe $recipe = null, RecipeRepository $recipes, IngredientRepository $ingredients, Ingredient $ingredient = null, IngredientCategorieRepository $ingredientCategories, IngredientCategorie $ingredientCategorie = null, FileUploader $fileUploader, UserPasswordHasherInterface $userPasswordHasher, CommentairesRepository $commentaires, Commentaires $commentaire = null): Response
    {
        $form = null;
        $element = null;
        if ($name == "users") {
            $element = $id != 'undefined' ? $user : new User();
            $form = $this->createForm(UserType::class, $element);
            $form->remove('oldpassword');
        }
        if ($name == "commentaires") {
            $element = $id != 'undefined' ? $commentaire : new Commentaires();
            $form = $this->createForm(CommentairesType::class, $element);
        }
        if ($name == "recipes") {
            $element = $id != 'undefined' ? $recipe : new Recipe();
            $form = $this->createForm(RecipeType::class, $element);
        }
        if ($name == "ingredients") {
            $element = $id != 'undefined' ? $ingredient : new Ingredient();
            $form = $this->createForm(IngredientType::class, $element);
        }
        if ($name == "ingredientCategories") {
            $element = $id  != 'undefined' ? $ingredientCategorie : new IngredientCategorie();
            $form = $this->createForm(IngredientCategorieType::class, $element);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($name == "users") {
                $element->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }
            if ($name == "recipes" | $name == "ingredients") {
                $file = $form->get('photo')->getData();
                if ($file) {
                    $FileName = $fileUploader->upload($file);
                    $element->setImage($FileName);
                }
            }
            ${$name}->add($element, true);

            return $this->redirectToRoute('admin_getAll', ['name' => $name], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('index.html.twig', [
            'data' => $element,
            'name' => $name,
            'form' => $form,
            'page_name' => 'admin_page_upate'
        ]);
    }

    /**
     * @Route("/delete/{id}/{name}", name="admin_delete",methods={"GET"})
     */
    public function delete(CommentairesRepository $commentaires, Commentaires $commentaire = null, $name, Request $request, UserRepository $users, User $user = null, Recipe $recipe = null, RecipeRepository $recipes, IngredientRepository $ingredients, Ingredient $ingredient = null, IngredientCategorieRepository $ingredientCategories, IngredientCategorie $ingredientCategorie = null): Response
    {

        $element = null;
        if ($name == "users") {
            $element = $user;
            foreach ($element->getCommentaires() as $el) {
                $commentaires->remove($el, true);
            }
            foreach ($element->getRecipes() as $recipe) {
                $recipes->remove($recipe, true);
            }
        }
        if ($name == "recipes") {
            $element = $recipe;
            foreach ($element->getCommentaires() as $el) {
                $commentaires->remove($el, true);
            }
        }
        if ($name == "ingredients") {
            $element = $ingredient;
            foreach ($element->getRecipes() as $el) {
                $el->removeIngredient($ingredient);
            }
            $element->setType(null);
        }
        if ($name == "ingredientCategories") {
            $element = $ingredientCategorie;
        }
        if ($name == "commentaires") {
            $element = $commentaire;
        }
        ${$name}->remove($element, true);

        return $this->render('index.html.twig', [
            'data' => ${$name}->findAll(),
            'name' => $name,
            'page_name' => 'admin_page_getAll'
        ]);
    }
}

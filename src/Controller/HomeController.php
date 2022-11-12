<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\User;
use App\Repository\IngredientCategorieRepository;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(RecipeRepository $recipes): Response
    {
        return $this->render('index.html.twig', [
            'page_name' => 'home'
        ]);
    }
    /**
     * @Route("/NotreEquipe", name="NotreEquipe")
     */
    public function index2(RecipeRepository $recipes): Response
    {
        return $this->render('index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'NotreEquipe'
        ]);
    }
    /**
     * @Route("/NousContacter", name="NousContacter")
     */
    public function index3(RecipeRepository $recipes): Response
    {
        return $this->render('index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'NousContacter'
        ]);
    }


    /**
     * @Route("/searchRecipe", name="searchRecipe",methods={"GET","POST"})
     */
    public function searchRecipe(Request $request, RecipeRepository $recipes, IngredientRepository $ingredients, IngredientCategorieRepository $ingredientsCategorie): Response
    {

        $startingName = $request->request->get('search');
        $necesseryIngredient = [];
        $necesseryIngredientCategorie = [];
        $param = "";
        $ableToDoWithFridge = false;
        foreach ($request->request as $key => $element) {
            $values = explode('|', $key);
            if ($values[0] == "checkbox") {
                switch ($values[1]) {
                    case 'ingredients':
                        array_push($necesseryIngredient, $values[2]);
                        break;
                    case 'ingredients_catégories':
                        array_push($necesseryIngredientCategorie, $values[2]);
                        break;
                    case 'ableToDoWithFridge':
                        $ableToDoWithFridge = true;
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }
        $res = $recipes->findAllWithParam($startingName);

        if ($ableToDoWithFridge && $this->getUser()) {
            foreach ($res as $key => $value) {
                if (!$this->getUser()->getFridge()->isInFridge($value->getIngredients())) unset($res[$key]);
            }
        }

        foreach ($res as $key => $value) {
            if (!$value->containIngredients($necesseryIngredient)) unset($res[$key]);
        }

        foreach ($res as $key => $value) {
            if (!$value->containIngredientsCategorie($necesseryIngredientCategorie)) unset($res[$key]);
        }

        return $this->render('index.html.twig', [
            'triSections' => [["ingredients", $ingredients->findAll()], ["ingredients catégories", $ingredientsCategorie->findAll()]],
            'recipes' => $res,
            'page_name' => 'searchRecipe'
        ]);
    }

    /**
     * @Route("/user/profil/{id}", name="get_user_profil",methods={"GET"})
     */
    public function getUserProfil(User $user): Response
    {
        if (!$user) return $this->redirectToRoute('app_home');
        return $this->render('index.html.twig', [
            'user' => $user,
            'userMessage' => $user->getCommentaires(),
            'userFavories' => $user->getFavories(),
            'page_name' => 'get_user_profil'
        ]);
    }
}

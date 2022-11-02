<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use App\Repository\RecipeRepository;
use DateTime;
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
    public function index(RecipeRepository $recipes): Response
    { 
       
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'home'
        ]);
    }

    
      /**
     * @Route("/searchRecipe/{id}", name="searchRecipe")
     */
    public function searchRecipe(Request $request,RecipeRepository $recipes,Recipe $recipe,UserInterface $user = null) : Response
    {
      //  $favs = null;
      //  if($this->getUser()){ $favs = $this->getUser()->getFavories();
      //   }

      /*  if (!$this->getUser()) return $this->redirectToRoute('app_home');   
        
        $form_recipe = $this->createForm(RecipeType::class, $recipe);
        $form_recipe->remove("photo");
        $form_recipe->remove("steps")
        ->remove('people')
        ->remove('budget')
        ->remove('tags')
        ->remove('preptime')
        ->remove('toltalTime')
        ->remove('Author')
        ->remove('difficulty');
        $form_recipe->handleRequest($request);


        if ($form_recipe->isSubmitted() && $form_recipe->isValid()) { 
            return $this->redirectToRoute('recipe', [], Response::HTTP_SEE_OTHER);
        }   */

        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'searchRecipe',
           // 'form_recipe' => $form_recipe->createView()
        ]);
    } 


}

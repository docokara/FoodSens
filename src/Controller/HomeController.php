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
            'page_name' => 'home'
        ]);
    }
     /**
     * @Route("/NotreEquipe", name="NotreEquipe")
     */
    public function index2(RecipeRepository $recipes): Response
    {       
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'NotreEquipe'
        ]);
    }
     /**
     * @Route("/NousContacter", name="NousContacter")
     */
    public function index3(RecipeRepository $recipes): Response
    { 
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'NousContacter'
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
       
        if (!$this->getUser()) return $this->redirectToRoute('app_home');   
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
            
            $name = $form_recipe->get('name')->getData();
            dump($name);
    
           // return $this->redirectToRoute('recipe', [], Response::HTTP_SEE_OTHER);
           return $this->render('home/index.html.twig', [
         
            'page_name' => 'findRecipe',
            
        ]);
        } else{

            return $this->render('home/index.html.twig', [
                'recipes' => $recipes->findAll(),
                'page_name' => 'searchRecipe',
                'form_recipe' => $form_recipe->createView(),
            ]);
        }  
    } 







    
 //#[Route(path: '/findRecipe/{name}', name: 'findRecipe')]
    /**
     * @Route("/findRecipe", name="findRecipe")
     */
    public function findRecipeByName(Request $request,RecipeRepository $recipes) : Response
    {   


    public function searchRecipe(RecipeRepository $recipes) : Response
    {

     /**
     * @Route("/user/profil/{id}", name="get_user_profil")
     */
    public function getUserProfil(User $user) : Response
    {
        if(!$user) return $this->redirectToRoute('app_home');
        return $this->render('home/index.html.twig', [
            'user' => $user,
            'userMessage' => $user->getCommentaires(),
            'userFavories' => $user->getFavories(),
            'page_name' => 'get_user_profil'
        ]);
    } 
}

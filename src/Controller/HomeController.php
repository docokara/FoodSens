<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Recipe;
use App\Entity\User;
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
     * @Route("/searchRecipe", name="searchRecipe")
     */
    public function searchRecipe(Request $request,RecipeRepository $recipes) : Response
    {
            $startingName = $request->request->get('search');
            $res = $recipes->findAllWithParam($startingName);
            return $this->render('index.html.twig', [
                'recipes' => $res,
                'page_name' => 'searchRecipe'
            ]);
          
    } 

     /**
     * @Route("/user/profil/{id}", name="get_user_profil")
     */
    public function getUserProfil(User $user) : Response
    {
        if(!$user) return $this->redirectToRoute('app_home');
        return $this->render('index.html.twig', [
            'user' => $user,
            'userMessage' => $user->getCommentaires(),
            'userFavories' => $user->getFavories(),
            'page_name' => 'get_user_profil'
        ]);
    } 
}

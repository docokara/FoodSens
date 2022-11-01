<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Recipe;
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
     * @Route("/searchRecipe", name="searchRecipe")
     */
    public function searchRecipe(RecipeRepository $recipes,UserInterface $user = null) : Response
    {
      //  $favs = null;
      //  if($this->getUser()){ $favs = $this->getUser()->getFavories();
      //   }
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'page_name' => 'searchRecipe'
        ]);
    } 
}

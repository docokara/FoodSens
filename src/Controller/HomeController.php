<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
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
            'controller_name' => 'HomeController',
            'recipes' => $recipes->findAll()
        ]);
    }
     /**
     * @Route("/like/{id}", name="app_like")
     */
    public function fav(Request $request,UserRepository $users ,RecipeRepository $recipes,Recipe $recipe,UserInterface $user = null): Response
    { 
        $isLiking = true;
        $fav = $recipe->getFav();
        foreach($fav as $id){
            if($id == $user){ 
            $isLiking = false;
            }
        }
        $recipe->addFav($user); 
        if(!$isLiking) $recipe->removeFav($user);
        $recipes->add($recipe,true);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'recipes' => $recipes->findAll()
        ]);
    }
}

<?php

namespace App\Controller;
use App\Entity\Fridge;
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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\FridgeRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
            dump($this->getUser());

        return $this->render('home/index.html.twig', [
            'info' => $this->getUser(),
            'name' => 'userProfile'
        ]);
    }
    /**
     * @Route("/edit/{id}/{onModify}", name="user_profil_edit")
     */
    public function editProfil(Request $request,UserRepository $users,User $user,$onModify,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        
        if (!$this->getUser()) return $this->redirectToRoute('app_home');       
        if($onModify == "pseudo"){
            $form = $this->createForm(UserType::class, $user);
            $form -> remove('roles');
            $form -> remove('isVerified');
            $form -> remove('email');
            $form -> remove('password');
        }
        if($onModify == "email"){
            $form = $this->createForm(UserType::class, $user);
            $form -> remove('roles');
            $form -> remove('isVerified');
            $form -> remove('pseudo');
            $form -> remove('password');
        }
        if($onModify == "password"){
            $form = $this->createForm(UserType::class, $user);
            $form -> remove('roles');
            $form -> remove('isVerified');
            $form -> remove('email');
            $form -> remove('pseudo');
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            if($onModify == "password"){
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('password')->getData()
                        )
                    );
            }   
            $users->add($user, true);
            return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/index.html.twig', [
            'data' => $user,
            'name' => 'userProfileEdition',
            'form' => $form,
            'onModify' => $onModify
        ]);
            
    }
   
   /**
   * @Route("/myFav/{id}", name="user_favories")
   */

    public function myFav(Request $request,User $user) : Response
    {
        
        return $this->render('home/index.html.twig', [
            'recipes' => $user->getFavories(),
            'name' => 'myFav'
        ]); 
    }

     /**
     * @Route("/myFridge/{id}", name="user_fridge")
     */
    public function myFridge(Request $request,User $user) : Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $fridge = $this->getUser()->getFridge();
        return $this->render('home/index.html.twig', [
            'ingredients' => $fridge->getIngredients(),
            'name' => 'myFridge'
        ]); 
    }

      /**
     * @Route("/addIngredient/{id}", name="user_addIngredient")
     */
    public function addIngredients($id,Request $request,FridgeRepository $fridges,UserRepository $users,UserInterface $user = null,Fridge $fridge = null) : Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $user = $this->getUser();
        $fridge = $user->getFridge();
        $form = $this->createForm(FridgeType::class, $fridge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFridge($fridge);
            $users->add($user,true);
            return $this->redirectToRoute('user_fridge', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'ingredients' => $fridge->getIngredients(),
            'name' => 'myFridge'
        ]); 
    }
      /**
     * @Route("/recipe/delete/{id}", name="user_recipe_delete")
     */
    public function deleteRecipe($id,Request $request,UserRepository $users,UserInterface $user = null,Recipe $recipe,RecipeRepository $recipes) : Response
    {
        $user = $this->getUser();
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        if ($user != $recipe->getAuthor() or (!in_array("ROLE_ADMIN", $user->getRoles()))) return $this->redirectToRoute('app_home');
        dump("delete");
        $recipes->remove($recipe,true);

        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'name' => 'allRecipe'
        ]);
    }

     /**
     * @Route("/recipe/edit/{id}", name="user_recipe_edit")
     */
    public function editRecipe(Request $request,UserInterface $user = null,Recipe $recipe,RecipeRepository $recipes) : Response
    {
        $user = $this->getUser();
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        if ($user != $recipe->getAuthor() or (!in_array("ROLE_ADMIN", $user->getRoles()))) return $this->redirectToRoute('app_home');
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipes->add($recipe,true);
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'name' => 'modifyRecipe',
            'form' => $form->createView()
        ]); 
    }
      /**
     * @Route("/recipe/create", name="user_recipe_create")
     */
    public function modifyRecipe(Request $request,UserInterface $user = null,Recipe $recipe = null,RecipeRepository $recipes) : Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipes->add($recipe,true);
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'name' => 'modifyRecipe',
            'form' => $form->createView()
        ]); 
    }
     /**
     * @Route("/like/{id}", name="user_like")
     */
    public function fav(Request $request,UserRepository $users ,RecipeRepository $recipes,Recipe $recipe,UserInterface $user = null): Response
    { 
        $favs = null;
        $isLiking = true;

        if (!$this->getUser()) return $this->redirectToRoute('app_home');
        
        $favs = $this->getUser()->getFavories();
        $fav = $recipe->getFav();
        foreach($fav as $id){
            if($id == $user){ 
            $isLiking = false;
            }
        }
        if(!$isLiking) $recipe->removeFav($user);
        else $recipe->addFav($user); 
        $recipes->add($recipe,true);
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes->findAll(),
            'favs' => $favs,
            'name' => 'allRecipe'
        ]);
    }
    
}

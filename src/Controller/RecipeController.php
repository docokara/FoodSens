<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaires;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{
    /**
     * @Route("/{id}/{editCom}", name="show_recipe")
     */
    public function showRecipe($id, $editCom, Request $request, Recipe $recipe = null, Commentaires $editedCom = null, CommentairesRepository $commentaires): Response
    {
        if ($recipe == null) return $this->redirectToRoute('app_home');
        $form = null;
        if ($this->getUser()) {
            $commentaire = null;

            if ($editCom != null) {
                $form = $this->createForm(CommentairesType::class, $editedCom);
                $form->handleRequest($request);
            } else {
                $commentaire = new Commentaires();
                $form = $this->createForm(CommentairesType::class, $commentaire);
                $form->handleRequest($request);
            }

            if ($form->isSubmitted() && $form->isValid()) {

                if ($editCom != null) {
                    $commentaires->add($editedCom, true);
                } else {
                    $commentaire->setDate(new DateTime());
                    $commentaire->setOwner($this->getUser());
                    $commentaire->setLocation($recipe);
                    $commentaires->add($commentaire, true);
                }
                return $this->redirectToRoute('show_recipe', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('index.html.twig', [
            'form' => $form != null ? $form->createView() : "undefined",
            'recipe' => $recipe,
            'editedComId' => $editCom ? $editedCom->getId() : 'undefined',
            'commentaires' => $recipe->getCommentaires(),
            'recipeAuthor' => $recipe->getAuthor(),
            'page_name' => 'showRecipe'
        ]);
    }
    /**
     * @Route("/delete/commentaire/{id}", name="recipe_delete_commentaire")
     */
    public function index($id, Commentaires $commentaire, CommentairesRepository $commentaires, UserRepository $users, RecipeRepository $recipes): Response
    {
        $user = $this->getUser();
        if (!$commentaire || !$user) return $this->redirectToRoute('app_home');
        if ($user != $commentaire->getOwner() && !in_array("ROLE_ADMIN", $user->getRoles())) return $this->redirectToRoute('show_recipe', ['id' => $commentaire->getLocation()->getId(), "editCom" => 'undefined'], Response::HTTP_SEE_OTHER);
        $userOwner = $commentaire->getOwner();
        $recipe = $commentaire->getLocation();

        $recipe->removeCommentaire($commentaire);
        $userOwner->removeCommentaire($commentaire);

        $commentaires->remove($commentaire, true);
        $users->add($userOwner, true);
        $recipes->add($recipe, true);

        return $this->redirectToRoute('show_recipe', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
    }
}

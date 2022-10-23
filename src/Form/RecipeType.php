<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\User;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredients',  EntityType::class, array(
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => true

            ))
            ->add('tags', FormType\CollectionType::class)
            ->add('steps',FormType\CollectionType::class)
            ->add('people')
            ->add('budget')
            ->add('difficulty')
            ->add('preptime')
            ->add('toltalTime')
            ->add('Author',EntityType::class,[
                'class' => User::class,
                'choice_label' => 'pseudo'
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\User;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('photo', FileType::class, [
                'label' => 'image de votre recette',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([])
                ],
            ])
            ->add('ingredients',  EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,

            ])
            ->add('tags', FormType\CollectionType::class)
            ->add('steps')
            ->add('people')
            ->add('budget')
            ->add('difficulty')
            ->add('preptime')
            ->add('Author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'pseudo'
            ]);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $product = $event;
            $form = $event->getForm();
            dump($product);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}

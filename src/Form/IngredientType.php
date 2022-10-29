<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
class IngredientType extends AbstractType
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
                    new File([
                       
                    ])
                    ],
            ])
            ->add('kcalFor100g')
            ->add('PriceFor100g')
            ->add('type', EntityType::class,[
                'class' => IngredientCategorie::class,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}

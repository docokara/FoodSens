<?php

namespace App\Form;

use App\Entity\Fridge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FridgeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('ingredients',  EntityType::class, [
            'expanded' => true,
            'class' => Ingredient::class,
            'multiple' => true
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fridge::class,
        ]);
    }
}

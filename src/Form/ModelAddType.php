<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model_type', ChoiceType::class, [
                'choices' => [
                    'Lokomotywa' => 'lokomotywa',
                    'Wagon' => 'wagon'
                ],
                'multiple' => false,
                'expanded' => false,
                'label' => 'Wybierz typ modelu'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Kontynuuj'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

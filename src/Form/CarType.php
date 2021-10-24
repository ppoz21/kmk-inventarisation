<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Train;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Numer wagonu'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Wagon osobowy' => 'WO',
                    'Wagon towarowy' => 'WT'
                ],
                'label' => 'Typ wagonu'
            ])
            ->add('comments', TextType::class, [
                'label' => 'Komentarz',
                'required' => false
            ])
//            ->add('photo') //TODO: Photo uplaoder
            ->add('train', EntityType::class, [
                'class' => Train::class,
                'choice_label' => 'locomotive.typeAndNumber',
                'label' => 'Przypisana lokomotywa'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zapisz model'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}

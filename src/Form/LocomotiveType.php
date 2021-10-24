<?php

namespace App\Form;

use App\Entity\Locomotive;
use App\Entity\Train;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class LocomotiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeAndNumber', TextType::class, [
                'label' => 'Typ i numer lokomotywy'
            ])
            ->add('painting', TextType::class, [
                'label' => 'Malowanie'
            ])
            ->add('shortName', TextType::class, [
                'label' => 'Nazwa skrócona',
                'required' => false
            ])
            ->add('owner', TextType::class, [
                'label' => 'Właściciel',
            ])
            ->add('comments', TextType::class, [
                'label' => 'Komentarz',
                'required' => false
            ])
//            ->add('photo', FileType::class, [
//                'mapped' => false,
//                'label' => 'Zdjęcie',
//                'required' => false,
//                'constraints' => [
//                    new Image(['maxSize' => '10M', 'maxSizeMessage' => 'Plik jest za duży ({{ size }} {{ suffix }}). Maksymalny rozmiar pliku to {{ limit }} {{ suffix }}.'])
//                ],
//                'attr' => [
//                    'accept' => 'image/*'
//                ]
//            ]) // TODO: Photo uplaoder
            ->add('train', TrainType::class, [
                'label' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zapisz model'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Locomotive::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\ToDoList;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

class TodoAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([], 'Pole wymagane')
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => false
            ])
            ->add('user', EntityType::class, [
                'label' => false,
                'class' => User::class,
                'multiple' => true,
                'required' => true,
                'constraints' => [
                    new Count(array(
                        'min' => 1,
                        'minMessage' => "Pole wymagane"
                    ))
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ToDoList::class,
        ]);
    }
}

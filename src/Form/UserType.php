<?php

namespace App\Form;

use App\Entity\Station;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adres e-mail'
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrator' => 'ROLE_ADMIN',
                    'Nadzór' => 'ROLE_NADZOR',
                    'Mikol' => 'ROLE_MIKOL',
                    'Użytkownik' => 'ROLE_USER'
                ],
                'multiple' => true,
                'expanded' => false,
                'label' => 'Uprawnienia',
                'help' => 'UWAGA! Nadanie uprawnień "Administastor" przydziela wszystkie uprawnienia',
                'help_attr' => [
                    'class' => 'text-sm-center text-info'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Imię',
                'constraints' => [
                    new NotBlank(['message' => 'Pole nie może być puste'])
                ]
            ])
            ->add('surname', TextType::class, [
                'label' => 'Nazwisko',
                'constraints' => [
                    new NotBlank(['message' => 'Pole nie może być puste'])
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numer telefonu',
                'constraints' => [
                    new Regex('/^\(\+48\) [0-9]{3}\-[0-9]{3}\-[0-9]{3}$/', 'Numer telefonu musi mieć postać "(+48) 999-999-999"')
                ]
            ])
            ->add('stations', EntityType::class, [
                'class' => Station::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Stacje',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Dodaj użytkownika'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

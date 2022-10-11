<?php

declare(strict_types=1);

namespace App\Form\Admin\Users;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditReaderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Adres email: '
            ])
            ->add('login', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Login: '
            ])
            ->add('isActive', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Status konta: ',
                'choices' => [
                    'Aktywny' => 1,
                    'Nie aktywny' => 0
                ]
            ])
            ->add('isBanned', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Zablokowany: ',
                'choices' => [
                    'Zablokowany' => true,
                    'Odblokowany' => false
                ]
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Edytuj użytkownika'
            ]);
    }
}
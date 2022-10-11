<?php

declare(strict_types=1);

namespace App\Form\Admin\Users;

use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditUserFormType extends AbstractType
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
            ->add('reservationsQuantity', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Liczba rezerwacji/wypożyczeń'
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Edytuj użytkownika'
            ]);
    }
}
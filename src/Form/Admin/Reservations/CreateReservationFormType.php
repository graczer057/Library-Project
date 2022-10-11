<?php

declare(strict_types=1);

namespace App\Form\Admin\Reservations;

use App\Entity\Books\Book;
use App\Entity\Users\Reader;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bookId', EntityType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Ksiązka: ',
                'class' => Book::class,
                'choice_label' => 'name',
            ])
            ->add('readerId', EntityType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Czytelnik: ',
                'class' => Reader::class,
                'choice_label' => 'userId.login',
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Utwórz rezerwację'
            ]);
    }
}
<?php

declare(strict_types=1);

namespace App\Form\Admin\Books;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class EditCoverFileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cover', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Okładka książki: ',
                'required' => false,
                'data_class' => null
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Edytuj okładkę'
            ]);
    }
}
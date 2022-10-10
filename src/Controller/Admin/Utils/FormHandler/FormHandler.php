<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils\FormHandler;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormHandler extends AbstractController
{
    public function checkForm(Request $request, string $formTypeClass, mixed $data = null): FormInterface
    {
        $form = $this->createForm($formTypeClass, $data);
        $form->handleRequest($request);

        return $form;
    }

    public static function getDataForm(FormInterface $form): array
    {
        return $form->getData();
    }
}
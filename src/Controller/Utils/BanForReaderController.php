<?php

declare(strict_types=1);

namespace App\Controller\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BanForReaderController extends AbstractController
{
    #[Route('/ban', name: 'banPage')]
    public function ban(): Response
    {
        return $this->render('Anon/Ban/banPage.html.twig');
    }
}
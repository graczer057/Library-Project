<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\Utils\CountObjects;
use App\Controller\Admin\Utils\FindObjects;
use App\Controller\Interfaces\HomepageInterface;
use App\Repository\Books\BookRepository;
use App\Repository\Users\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomepageController extends AbstractController implements HomepageInterface
{
    public function __construct(
        private readonly UserRepository  $usersRepository,
        private readonly BookRepository $booksRepository
    ) {
    }

    #[Route('/admin/', name: 'adminHomepage')]
    public function homepage(UserInterface $user): Response
    {
        return $this->render('Admin/Homepage/homepage.html.twig', [
            'users' => CountObjects::count(FindObjects::findAllObjects($this->usersRepository)),
            'books' => CountObjects::count(FindObjects::findAllObjects($this->booksRepository))
        ]);
    }
}
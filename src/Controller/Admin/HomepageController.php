<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Interfaces\HomepageInterface;
use App\Repository\Books\BooksRepository;
use App\Repository\Users\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomepageController extends AbstractController implements HomepageInterface
{
    public function __construct(
        private readonly UsersRepository $usersRepository,
        private readonly BooksRepository $booksRepository
    ) {

    }

    #[Route('/admin/', name: 'adminHomepage')]
    public function homepage(UserInterface $user): Response
    {
        $users = $this->usersRepository->findAll();

        $books = $this->booksRepository->findAll();

        $countUsers = count($users);
        $countBooks = count($books);

        return $this->render('Admin/Homepage/homepage.html.twig', [
            'users' => $countUsers,
            'books' => $countBooks
        ]);
    }
}
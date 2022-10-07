<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Controller\Admin\Interfaces\ListObjectsInterface;
use App\Repository\Users\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListUsers extends AbstractController implements ListObjectsInterface
{
    public function __construct(
        private readonly UsersRepository $usersRepository
    ) {

    }

    #[Route('/admin/user/list', name: 'adminListUsers', methods: ['GET'])]
    public function list(): Response
    {
        $users = $this->usersRepository->findBy([], ['id' => 'ASC']);

        return $this->render('Admin/Users/listUsers.html.twig', [
            'users' => $users
        ]);
    }
}
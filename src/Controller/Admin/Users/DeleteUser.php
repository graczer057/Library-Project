<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Controller\Admin\Interfaces\DeleteObjectInterface;
use App\Repository\Users\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteUser extends AbstractController implements DeleteObjectInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UsersRepository $usersRepository
    ) {

    }

    #[Route('/admin/user/delete/{id}', name: 'adminDeleteUser', methods: ['GET', 'POST'])]
    public function delete(int $id): Response
    {
        $specificUser = $this->usersRepository->findOneBy(['id' => $id]);

        if (!$specificUser) {
            $this->addFlash('error', 'Przykro nam, ale podany użytkownik nie istnieje');

            return $this->redirectToRoute('adminListUsers');
        }

        $this->entityManager->remove($specificUser);
        $this->entityManager->flush();

        $this->addFlash('success', 'Udało się usunąć użytkownika');

        return $this->redirectToRoute('adminListUsers');
    }
}
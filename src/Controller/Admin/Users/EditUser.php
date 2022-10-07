<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Controller\Admin\Interfaces\EditObjectInterface;
use App\Form\Admin\Users\EditUserFormType;
use App\Repository\Users\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditUser extends AbstractController implements EditObjectInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UsersRepository $usersRepository
    ) {

    }

    #[Route('/admin/user/edit/{id}', name: 'adminEditUser', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $specificUser = $this->usersRepository->findOneBy(['id' => $id]);

        if (!$specificUser) {
          $this->addFlash('error', 'Przykro nam, ale podany użytkownik nie istnieje');

          return $this->redirectToRoute('adminListUsers');
        }

        $form = $this->createForm(EditUserFormType::class, $specificUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($specificUser);
            $this->entityManager->flush();

            $this->addFlash('success', 'Użytkownik został pomyślnie edytowany');

            return $this->redirectToRoute('adminListUsers');
        }

        return $this->render('Admin/Users/editUserForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Controller\Admin\Interfaces\CreateObjectInterface;
use App\Entity\Users\Users;
use App\Form\Admin\Users\CreateUserFormType;
use App\Repository\Users\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateUser extends AbstractController implements CreateObjectInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UsersRepository $usersRepository
    ) {
    }

    #[Route('/admin/user/create', name: 'adminCreateUser', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $form = $this->createForm(CreateUserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $existingUserEmail = $this->usersRepository->findOneBy(['email' => $formData['email']]);

            $existingUserLogin = $this->usersRepository->findOneBy(['login' => $formData['login']]);

            if ($existingUserEmail || $existingUserLogin) {
                $this->addFlash('error', 'Użytkownik z podanym emailem lub loginem już istnieje.');

                return $this->render('Admin/Users/createUserForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $newUser = new Users(
                $formData['email'],
                $formData['login'],
                $formData['password'],
                $formData['roles']
            );

            $this->entityManager->persist($newUser);
            $this->entityManager->flush();

            $this->addFlash('success', 'Dodawanie nowego użytkownika zakończone pomyślnie');

            return $this->redirectToRoute('adminListUsers');
        }

        return $this->render('Admin/Users/createUserForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
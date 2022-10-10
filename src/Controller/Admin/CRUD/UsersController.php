<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD;

use App\Controller\Admin\Interfaces\CRUDInterface;
use App\Controller\Admin\Utils\EntityManagerCommands;
use App\Controller\Admin\Utils\FindExistingObject\FindExistingObjects;
use App\Controller\Admin\Utils\FindObjects;
use App\Controller\Admin\Utils\FormHandler\FormHandler;
use App\Controller\Admin\Utils\ObjectsCommands\CreateObject;
use App\Form\Admin\Users\CreateUserFormType;
use App\Form\Admin\Users\EditUserFormType;
use App\Repository\Users\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class UsersController extends AbstractController implements CRUDInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $usersRepository,
        private readonly FormHandler $formHandler
    ) {
    }

    #[Route('/user/create', name: 'adminCreateUser', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $form = $this->formHandler->checkForm($request, CreateUserFormType::class);

        if ( ($form->isSubmitted()) && ($form->isValid()) ) {
            $data = FormHandler::getDataForm($form);

            try {
                FindExistingObjects::findExistingUser($this->usersRepository, $data['email'], $data['login']);
            } catch (Exception) {
                $this->addFlash('error', 'Przykro nam, ale użytkownik o podanym emailu lub loginie już istnieje, prosimy o wprowadzenie innych danych');
                return $this->render('Admin/Users/createUserForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $newUser = CreateObject::createUser($data);

            $this->entityManager->persist($newUser);
            $this->entityManager->flush();

            $this->addFlash('success', 'Użytkownik został pomyślnie dodany.');

            return $this->redirectToRoute('adminListUsers');
        }

        return $this->render('Admin/Users/createUserForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/edit/{id}', name: 'adminEditUser', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $specificUser = FindObjects::findObjectById($this->usersRepository, $id);

        try {
            FindExistingObjects::findExistingObject($specificUser);
        } catch (Exception){
            $this->addFlash('error', 'Przykro nam, ale podany użytkownik nie istnieje');

            return $this->redirectToRoute('adminListUsers');
        }

        $form = $this->formHandler->checkForm($request, EditUserFormType::class, $specificUser);

        if ( ($form->isSubmitted()) && ($form->isValid())) {
            EntityManagerCommands::persistObject($this->entityManager, $specificUser);

            $this->addFlash('success' , 'Użytkownik został pomyślnie edytowany.');

            return $this->redirectToRoute('adminListUsers');
        }

        return $this->render('Admin/Users/editUserForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/list', name: 'adminListUsers', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('Admin/Users/listUsers.html.twig', [
            'users' => FindObjects::findAllObjects($this->usersRepository)
        ]);
    }

    #[Route('/user/delete/{id}', name: 'adminDeleteUser', methods: ['GET', 'POST'])]
    public function delete(int $id): Response
    {
        $specificUser = FindObjects::findObjectById($this->usersRepository, $id);

        try {
            FindExistingObjects::findExistingObject($specificUser);
        } catch (Exception){
            $this->addFlash('error', 'Przykro nam, ale podany użytkownik nie istnieje');

            return $this->redirectToRoute('adminListUsers');
        }

        EntityManagerCommands::removeObject($this->entityManager, $specificUser);

        $this->addFlash('success', 'Udało się usunąć użytkownika');

        return $this->redirectToRoute('adminListUsers');
    }
}
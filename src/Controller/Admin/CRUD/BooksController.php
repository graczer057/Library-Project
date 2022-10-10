<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD;

use App\Controller\Admin\Interfaces\CRUDInterface;
use App\Controller\Admin\Utils\CheckValue;
use App\Controller\Admin\Utils\EntityManagerCommands;
use App\Controller\Admin\Utils\FindExistingObject\FindExistingObjects;
use App\Controller\Admin\Utils\FindObjects;
use App\Controller\Admin\Utils\FormHandler\FormHandler;
use App\Controller\Admin\Utils\ObjectsCommands\CreateObject;
use App\Entity\Books\Book;
use App\Form\Admin\Books\CreateBookFormType;
use App\Form\Admin\Books\EditBookFormType;
use App\Repository\Books\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class BooksController extends AbstractController implements CRUDInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookRepository $booksRepository,
        private readonly FormHandler $formHandler
    ) {
    }

    #[Route('/book/create', name: 'adminCreateBook', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $form = $this->formHandler->checkForm($request, CreateBookFormType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = FormHandler::getDataForm($form);

            $existingBookName = FindObjects::findObjectByName($this->booksRepository, $data['name']);

            try {
                FindExistingObjects::findExistingObject($existingBookName);
            } catch (Exception) {
                $this->addFlash('error', 'Książka z podanym tytułem już istnieje.');

                return $this->render('Admin/Books/createBookForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                CheckValue::checkGreaterValue($data['quantity'], 0);
            } catch (Exception) {
                $this->addFlash('error', 'Ilość książek trzeba podać jako liczba większa lub równa zeru.');

                return $this->render('Admin/Books/createBookForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $newBook = CreateObject::createBook($data);

            EntityManagerCommands::persistObject($this->entityManager, $newBook);

            $this->addFlash('success', 'Dodawanie nowej książki zakończone pomyślnie');

            return $this->redirectToRoute('adminListBooks');
        }

        return $this->render('Admin/Books/createBookForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/book/edit/{id}', name: 'adminEditBook', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $specificBook = FindObjects::findObjectById($this->booksRepository, $id);

        try {
            FindExistingObjects::findExistingObject($specificBook);
        } catch (Exception) {
            $this->addFlash('error', 'Przykro nam, ale podana książka nie istnieje');

            return $this->redirectToRoute('adminListBooks');
        }

        $form = $this->formHandler->checkForm($request, EditBookFormType::class, $specificBook);

        if ( ($form->isSubmitted()) && ($form->isValid()) ) {
            EntityManagerCommands::persistObject($this->entityManager, $specificBook);

            $this->addFlash('success', 'Książka została pomyślnie edytowana');

            return $this->redirectToRoute('adminListBooks');
        }

        return $this->render('Admin/Books/editBookForm.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/book/list', name: 'adminListBooks', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('Admin/Books/listBooks.html.twig', [
            'availableBooks' => $this->booksRepository->findByQuantity(0),
            'unavailableBooks' => $this->booksRepository->findBy(array('quantity' => 0), ['id' => 'ASC'])
        ]);
    }

    #[Route('/book/delete/{id}', name: 'adminDeleteBook', methods:['GET', 'POST'])]
    public function delete(int $id): Response
    {
        $specificBook = FindObjects::findObjectById($this->booksRepository, $id);

        try {
            FindExistingObjects::findExistingObject($specificBook);
        } catch (Exception) {
            $this->addFlash('error', 'Przykro nam, ale podana książka nie istnieje');

            return $this->redirectToRoute('adminListBooks');
        }

        EntityManagerCommands::removeObject($this->entityManager, $specificBook);

        $this->addFlash('success', 'Udało się usunąć książkę');

        return $this->redirectToRoute('adminListBooks');
    }
}
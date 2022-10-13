<?php

declare(strict_types=1);

namespace App\Controller\Admin\Actions;

use App\Controller\Admin\Interfaces\CRUDInterface;
use App\Controller\Admin\Utils\CheckValue;
use App\Controller\Admin\Utils\EntityManagerCommands;
use App\Controller\Admin\Utils\FindExistingObject\FindExistingObjects;
use App\Controller\Admin\Utils\FormHandler\FormHandler;
use App\Entity\Books\Book;
use App\Form\Admin\Books\CreateBookFormType;
use App\Form\Admin\Books\EditBookFormType;
use App\Form\Admin\Books\EditCoverFileFormType;
use App\Repository\Books\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
class BooksController extends AbstractController implements CRUDInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookRepository $booksRepository,
        private readonly FormHandler $formHandler,
        private readonly SluggerInterface $slugger
    ) {
    }

    #[Route('/book/create', name: 'adminCreateBook', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $form = $this->formHandler->checkForm($request, CreateBookFormType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = FormHandler::getDataForm($form);

            try {
                FindExistingObjects::findExistingObject($this->booksRepository, 'name', $data['name'], false);
            } catch (Exception) {
                $this->addFlash('error', 'Książka z podanym tytułem już istnieje.');

                return $this->render('Admin/Books/createBookForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                CheckValue::checkValue($data['quantity'], 0, true);
            } catch (Exception) {
                $this->addFlash('error', 'Ilość książek trzeba podać jako liczba większa lub równa zeru.');

                return $this->render('Admin/Books/createBookForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $pictureCoverName = $data['cover'];

            $newName = null;

            if ($pictureCoverName) {
                $originalName = pathinfo($pictureCoverName->getClientOriginalName(), PATHINFO_FILENAME);

                $safeName = $this->slugger->slug($originalName);

                $newName = $safeName.'-'.uniqid().'.'.$pictureCoverName->guessExtension();

                try {
                    $pictureCoverName->move(
                        $this->getParameter('covers'),
                        $newName
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', $e);
                }
            }

            $newBook = new Book(
                $data['name'],
                $data['author'],
                $data['description'],
                $data['quantity'],
                $newName
            );

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
        try {
            $specificBook = FindExistingObjects::findExistingObject($this->booksRepository, 'id', $id, true);
        } catch (Exception) {
            $this->addFlash('error', 'Przykro nam, ale podana książka nie istnieje');

            return $this->redirectToRoute('adminListBooks');
        }

        $form = $this->formHandler->checkForm($request, EditBookFormType::class, $specificBook);

        if ( ($form->isSubmitted()) && ($form->isValid()) ) {
            try {
                //FindExistingObjects::findExistingObject($this->booksRepository, 'name', $specificBook->getName(), false);
                CheckValue::checkValue($specificBook->getQuantity(), 0, true);
            } catch (Exception) {
                $this->addFlash('error', 'Ilość książek trzeba podać jako liczba większa lub równa zeru.');

                return $this->render('Admin/Books/createBookForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            EntityManagerCommands::persistObject($this->entityManager, $specificBook);

            $this->addFlash('success', 'Książka została pomyślnie edytowana');

            return $this->redirectToRoute('adminListBooks');
        }

        return $this->render('Admin/Books/editBookForm.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/book/edit/cover/{id}', name: 'adminEditBookCover', methods: ['GET', 'POST'])]
    public function editCover(int $id, Request $request): Response
    {
        try {
            $specificBook = FindExistingObjects::findExistingObject($this->booksRepository, 'id', $id, true);
        } catch (Exception) {
            $this->addFlash('error', 'Przykro nam, ale podana książka nie istnieje');

            return $this->redirectToRoute('adminListBooks');
        }

        $form = $this->formHandler->checkForm($request, EditCoverFileFormType::class);

        if ( ($form->isSubmitted()) && ($form->isValid()) ) {
            try {
                CheckValue::checkValue($specificBook->getQuantity(), 0, true);
            } catch (Exception) {
                $this->addFlash('error', 'Ilość książek trzeba podać jako liczba większa lub równa zeru.');

                return $this->render('Admin/Books/createBookForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $data = $form->getData();

            $pictureCoverName = $data['cover'];

            $oldName = $specificBook->getCover();

            $newName = null;

            if ($oldName != null) {
                $fileSystem = new Filesystem();
                $fileSystem->remove('uploads/covers/'.$oldName);
            }

            if ($pictureCoverName) {
                $originalName = pathinfo($pictureCoverName->getClientOriginalName(), PATHINFO_FILENAME);

                $safeName = $this->slugger->slug($originalName);

                $newName = $safeName.'-'.uniqid().'.'.$pictureCoverName->guessExtension();

                try {
                    $pictureCoverName->move(
                        $this->getParameter('covers'),
                        $newName
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', $e);
                }
            }

            $specificBook->setCover($newName);

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
        try {
            $specificBook = FindExistingObjects::findExistingObject($this->booksRepository, 'id', $id, true);
        } catch (Exception) {
            $this->addFlash('error', 'Przykro nam, ale podana książka nie istnieje');

            return $this->redirectToRoute('adminListBooks');
        }

        $coverFile = $specificBook->getCover();

        $fileSystem = new Filesystem();
        $fileSystem->remove('uploads/covers/'.$coverFile);

        EntityManagerCommands::removeObject($this->entityManager, $specificBook);

        $this->addFlash('success', 'Udało się usunąć książkę');

        return $this->redirectToRoute('adminListBooks');
    }
}
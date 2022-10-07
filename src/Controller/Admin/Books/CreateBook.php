<?php

declare(strict_types=1);

namespace App\Controller\Admin\Books;

use App\Controller\Admin\Interfaces\CreateObjectInterface;
use App\Entity\Books\Books;
use App\Form\Admin\Books\CreateBookFormType;
use App\Repository\Books\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateBook extends AbstractController implements CreateObjectInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BooksRepository $booksRepository
    ) {

    }

    #[Route('/admin/book/create', name: 'adminCreateBook', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $form = $this->createForm(CreateBookFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $existingBookName = $this->booksRepository->findOneBy(['name' => $formData['name']]);

            if ($existingBookName) {
                $this->addFlash('error', 'Książka z podanym tytułem już istnieje już istnieje.');

                return $this->render('Admin/Books/createBookForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            if ($formData['quantity'] < 0) {
                $this->addFlash('error', 'Ilość książek trzeba podać jako liczba większa lub równa zeru.');

                return $this->render('Admin/Books/createBookForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $newBook = new Books(
                $formData['name'],
                $formData['author'],
                $formData['description'],
                $formData['quantity']
            );

            $this->entityManager->persist($newBook);
            $this->entityManager->flush();

            $this->addFlash('success', 'Dodawanie nowej książki zakończone pomyślnie');

            return $this->redirectToRoute('adminListBooks');
        }

        return $this->render('Admin/Books/createBookForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
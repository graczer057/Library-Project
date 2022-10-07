<?php

declare(strict_types=1);

namespace App\Controller\Admin\Books;

use App\Controller\Admin\Interfaces\EditObjectInterface;
use App\Form\Admin\Books\EditBookFormType;
use App\Repository\Books\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditBook extends AbstractController implements EditObjectInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BooksRepository $booksRepository
    ) {

    }

    #[Route('/admin/book/edit/{id}', name: 'adminEditBook', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $specificBook = $this->booksRepository->findOneBy(['id' => $id]);

        if (!$specificBook) {
            $this->addFlash('error', 'Przykro nam, ale podana książka nie istnieje');

            return $this->redirectToRoute('adminListBooks');
        }

        $form = $this->createForm(EditBookFormType::class, $specificBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($specificBook);
            $this->entityManager->flush();

            $this->addFlash('success', 'Książka została pomyślnie edytowana');

            return $this->redirectToRoute('adminListBooks');
        }

        return $this->render('Admin/Books/editBookForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
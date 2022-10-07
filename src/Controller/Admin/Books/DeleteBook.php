<?php

declare(strict_types=1);

namespace App\Controller\Admin\Books;

use App\Controller\Admin\Interfaces\DeleteObjectInterface;
use App\Repository\Books\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteBook extends AbstractController implements DeleteObjectInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BooksRepository $booksRepository
    ) {

    }

    #[Route('/admin/book/delete/{id}', name: 'adminDeleteBook', methods:['GET', 'POST'])]
    public function delete(int $id): Response
    {
        $specificBook = $this->booksRepository->findOneBy(['id' => $id]);

        if (!$specificBook) {
            $this->addFlash('error', 'Przykro nam, ale podana książka nie istnieje');

            return $this->redirectToRoute('adminListBooks');
        }

        $this->entityManager->remove($specificBook);
        $this->entityManager->flush();

        $this->addFlash('success', 'Udało się usunąć książkę');

        return $this->redirectToRoute('adminListBooks');
    }
}
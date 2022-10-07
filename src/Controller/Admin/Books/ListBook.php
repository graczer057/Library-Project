<?php

declare(strict_types=1);

namespace App\Controller\Admin\Books;

use App\Controller\Admin\Interfaces\ListObjectsInterface;
use App\Repository\Books\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListBook extends AbstractController implements ListObjectsInterface
{
    public function __construct(
        private readonly BooksRepository $booksRepository
    ) {

    }

    #[Route('/admin/book/list', name: 'adminListBooks', methods: ['GET'])]
    public function list(): Response
    {
        $availableBooks = $this->booksRepository->findByQuantity(0);
        $unavailableBooks = $this->booksRepository->findBy(array('quantity' => 0), ['id' => 'ASC']);

        return $this->render('Admin/Books/listBooks.html.twig', [
            'availableBooks' => $availableBooks,
            'unavailableBooks' => $unavailableBooks
        ]);
    }
}
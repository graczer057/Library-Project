<?php

declare(strict_types=1);

namespace App\Controller\Admin\Rents;

use App\Controller\Admin\Interfaces\ListObjectsInterface;
use App\Repository\Books\RentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListRentedBooks extends AbstractController implements ListObjectsInterface
{
    public function __construct(
        private readonly RentRepository $rentsRepository
    ) {

    }

    #[Route('/admin/book/rent/list', name: 'adminListRentedBooks', methods: ['GET', 'POST'])]
    public function list(): Response
    {
        $rentedBooks = $this->rentsRepository->findBy([], ['id' => 'ASC']);

        $todayDate = new \DateTime("now");

        foreach ($rentedBooks as $rentedBook) {
            if (($todayDate->getTimestamp() < $rentedBook->getExpireDate()->getTimestamp())) {
                $actualRent[] = $rentedBook;
            } else {
                $expiredRent[] = $rentedBook;
            }
        }

        return $this->render('Admin/Rents/listRentedBooks.html.twig', [
            'actualRent' => $actualRent ?? null,
            'expiredRent' => $expiredRent ?? null
        ]);
    }
}
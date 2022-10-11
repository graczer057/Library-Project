<?php

declare(strict_types=1);

namespace App\Controller\Admin\Actions;

use App\Controller\Admin\Utils\FindObjects;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Books\RentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/book/rent')]
class RentsController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RentRepository $rentRepository
    ) {
    }

    #[Route('/list', name: 'adminListRentedBooks', methods: ['GET', 'POST'])]
    public function list(): Response
    {
        $rentedBooks = $this->rentRepository->findBy([], ['id' => 'ASC']);

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

    public function return(): Response
    {

    }


}
<?php

declare(strict_types=1);

namespace App\Controller\Admin\Reservations;

use App\Controller\Admin\Interfaces\ListObjectsInterface;
use App\Repository\Books\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListReservations extends AbstractController implements ListObjectsInterface
{
    public function __construct(
        private readonly ReservationRepository $reservationsRepository
    ) {
    }

    #[Route('/admin/reservation/list', name: 'adminListReservation', methods: ['GET', 'POST'])]
    public function list(): Response
    {
        $rentedReservations = $this->reservationsRepository->findBy(['isRented' => true], ['id' => 'ASC']);
        $notRentedReservations = $this->reservationsRepository->findBy(['isRented' => false], ['id' => 'ASC']);

        return $this->render('Admin/Reservations/listReservations.html.twig', [
            'rentedReservations' => $rentedReservations,
            'notRentedReservations' => $notRentedReservations
        ]);
    }
}
<?php

declare(strict_types=1);

namespace App\Controller\Reader\Reservations;

use App\Repository\Books\BookRepository;
use App\Repository\Books\ReservationRepository;
use App\Repository\Users\ReaderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ListReservation extends AbstractController
{
    public function __construct(
        private readonly ReservationRepository $reservationRepository,
        private readonly ReaderRepository $readerRepository
    ) {
    }

    #[Route('/reader/list/reservations', name: 'readerListReservations', methods: ['GET', 'POST'])]
    public function list(UserInterface $user): Response
    {
        $reader = $this->readerRepository->findOneBy(['userId' => $user->getId()]);
        $reservations = $this->reservationRepository->findBy(['readerId' => $reader->getId()]);

        return $this->render('Reader/Reservations/readerListReservations.html.twig', [
            'reservations' => $reservations
        ]);
    }
}
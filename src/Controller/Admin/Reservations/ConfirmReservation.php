<?php

declare(strict_types=1);

namespace App\Controller\Admin\Reservations;

use App\Entity\Books\Rent;
use App\Repository\Books\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmReservation extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ReservationRepository $reservationsRepository
    ) {

    }

    #[Route('/admin/reservation/confirm/{reservationId}', name: 'adminConfirmReservation', methods: ['GET', 'POST'])]
    public function confirm(int $reservationId): Response
    {
        $specificReservation = $this->reservationsRepository->findOneBy(['id' => $reservationId]);

        if (!$specificReservation) {
            $this->addFlash('error', 'Przykro nam, ale podana rezerwacja nie istnieje.');

            return $this->redirectToRoute('adminListReservation');
        }

        $newRent = new Rent(
            $specificReservation
        );

        $specificReservation->setIsRented(true);

        $this->entityManager->persist($newRent);
        $this->entityManager->persist($specificReservation);
        $this->entityManager->flush();

        $this->addFlash('success', 'Pomyślnie wypożyczono książkę');

        return $this->redirectToRoute('adminListReservation');
    }
}
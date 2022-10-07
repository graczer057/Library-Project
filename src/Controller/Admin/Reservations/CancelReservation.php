<?php

declare(strict_types=1);

namespace App\Controller\Admin\Reservations;

use App\Repository\Books\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CancelReservation extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ReservationsRepository $reservationsRepository
    ) {

    }

    #[Route('/admin/reservation/cancel/{reservationId}', name: 'adminCancelReservation', methods: ['GET', 'POST'])]
    public function cancel(int $reservationId): Response
    {
        $specificReservation = $this->reservationsRepository->findOneBy(['id' => $reservationId]);

        if (!$specificReservation) {
            $this->addFlash('error', 'Przykro nam, ale podana rezerwacja nie istnieje.');

            return $this->redirectToRoute('adminListReservation');
        }

        $bookQuantity = $specificReservation->getBookId()->getQuantity();
        $bookNewQuantity = $specificReservation->getBookId()->setQuantity($bookQuantity + 1);

        $readerReservationsQuantity = $specificReservation->getReaderId()->getReservationsQuantity();
        $newReaderReservationsQuantity = $specificReservation->getReaderId()->setReservationsQuantity($readerReservationsQuantity + 1);

        $this->entityManager->remove($specificReservation);
        $this->entityManager->persist($bookNewQuantity);
        $this->entityManager->persist($newReaderReservationsQuantity);
        $this->entityManager->flush();

        $this->addFlash('success', 'Rezerwacja anulowana pomyślnie');

        return $this->redirectToRoute('adminListReservation');
    }
}
<?php

declare(strict_types=1);

namespace App\Controller\Reader\Reservations;

use App\Repository\Books\BookRepository;
use App\Repository\Books\ReservationRepository;
use App\Repository\Users\ReaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CancelReservation extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookRepository         $booksRepository,
        private readonly ReaderRepository       $readersRepository,
        private readonly ReservationRepository $reservationsRepository
    ) {

    }

    #[Route('/reader/reservation/cancel/{bookId}', name: 'readerCancelReservation', methods: ['GET', 'POST'])]
    public function cancel(int $bookId, UserInterface $user): Response
    {
        $specificBook = $this->booksRepository->findOneBy(['id' => $bookId]);

        if (!$specificBook) {
            $this->addFlash('error', 'Przepraszamy, ale podana książka nie istnieje');

            return $this->redirectToRoute('readerHomepage');
        }


        $reader = $this->readersRepository->findOneBy(['userId' => $user->getId()]);

        $reservation = $this->reservationsRepository->findOneBy(['readerId' => $reader, 'bookId' => $bookId]);

        $bookQuantity = $specificBook->getQuantity();

        $readerReservationsQuantity = $reader->getReservationsQuantity();

        /*if ($bookQuantity <= 0 || $readerReservationsQuantity <= 0) {
            $this->addFlash('error', 'Przepraszamy, ale nie możesz zarezerwować tej książki');

            return $this->redirectToRoute('readerHomepage');
        }*/

        $readerNewQuantity = $reader->setReservationsQuantity($readerReservationsQuantity + 1);

        $bookNewQuantity = $specificBook->setQuantity($bookQuantity + 1);

        $this->entityManager->remove($reservation);
        $this->entityManager->persist($readerNewQuantity);
        $this->entityManager->persist($bookNewQuantity);
        $this->entityManager->flush();

        /*return $this->redirectToRoute('readerHomepage');*/

        return new JsonResponse([
            'status' => 'success',
            'statusMsg' => 'Właśnie usunąłeś rezerwację'
        ],   200);
    }
}
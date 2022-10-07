<?php

declare(strict_types=1);

namespace App\Controller\Reader\Reservations;

use App\Entity\Books\Reservations;
use App\Repository\Books\BooksRepository;
use App\Repository\Books\ReservationsRepository;
use App\Repository\Users\ReadersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateReservation extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ReadersRepository $readersRepository,
        private readonly ReservationsRepository $reservationsRepository,
        private readonly BooksRepository $booksRepository
    ) {

    }

    #[Route('/reader/reservation/create/{bookId}', name: 'readerCreateReservation', methods: ['GET', 'POST'])]
    public function create(int $bookId, UserInterface $user): Response
    {
        $specificBook = $this->booksRepository->findOneBy(['id' => $bookId]);

        if (!$specificBook) {
            $this->addFlash('error', 'Przepraszamy, ale podana książka nie istnieje');

            return $this->redirectToRoute('readerHomepage');
        }

        $bookQuantity = $specificBook->getQuantity();

        $reader = $this->readersRepository->findOneBy(['userId' => $user->getId()]);

        $isReservedAlready = $this->reservationsRepository->findOneBy(['readerId' => $reader, 'bookId' => $bookId]);

        if ($isReservedAlready) {
            $this->addFlash('error', 'Sory, ale już zarezerwowałeś tą książkę');

            return $this->redirectToRoute('readerHomepage');
        }

        $readerReservationsQuantity = $reader->getReservationsQuantity();

        if ($bookQuantity <= 0 || $readerReservationsQuantity <= 0) {
            $this->addFlash('error', 'Przepraszamy, ale nie możemy zarezerwować książki');

            return $this->redirectToRoute('readerHomepage');
        }

        $newReservation = new Reservations(
            $reader,
            $specificBook
        );

        $readerNewQuantity = $reader->setReservationsQuantity($readerReservationsQuantity - 1);

        $bookNewQuantity = $specificBook->setQuantity($bookQuantity - 1);

        $this->entityManager->persist($newReservation);
        $this->entityManager->persist($readerNewQuantity);
        $this->entityManager->persist($bookNewQuantity);
        $this->entityManager->flush();

        return $this->redirectToRoute('readerHomepage');
    }
}
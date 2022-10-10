<?php

declare(strict_types=1);

namespace App\Controller\Reader;

use App\Controller\Interfaces\HomepageInterface;
use App\Repository\Books\BookRepository;
use App\Repository\Books\RentRepository;
use App\Repository\Books\ReservationRepository;
use App\Repository\Users\ReaderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomepageController extends AbstractController implements HomepageInterface
{
    public function __construct(
        private readonly BookRepository        $booksRepository,
        private readonly ReaderRepository      $readersRepository,
        private readonly ReservationRepository $reservationsRepository,
        private readonly RentRepository        $rentsRepository
    ) {
    }

    #[Route('/reader/', name: 'readerHomepage')]
    public function homepage(UserInterface $user): Response
    {
        $books = $this->booksRepository->findBy([], ['id' => 'DESC']);

        $reader = $this->readersRepository->findOneBy(['userId' => $user->getId()]);

        $reservations = $this->reservationsRepository->findBy(['readerId' => $reader->getId()]);

        foreach ($reservations as $reservation) {
            if ($reservation->getIsRented()) {
                $rent[] = $this->rentsRepository->findOneBy(['reservationId' => $reservation->getId()]);
            } else {
                $notRented[] = $reservation;
            }
        }

        return $this->render('Reader/Homepage/homepage.html.twig', [
            'books' => $books,
            'reader' => $reader,
            'reservations' => $notRented ?? null,
            'rentInfo' => $rent ?? null
        ]);
    }
}
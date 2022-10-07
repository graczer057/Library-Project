<?php

declare(strict_types=1);

namespace App\Controller\Reader;

use App\Controller\Interfaces\HomepageInterface;
use App\Repository\Books\BooksRepository;
use App\Repository\Books\RentsRepository;
use App\Repository\Books\ReservationsRepository;
use App\Repository\Users\ReadersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomepageController extends AbstractController implements HomepageInterface
{
    public function __construct(
        private readonly BooksRepository $booksRepository,
        private readonly ReadersRepository $readersRepository,
        private readonly ReservationsRepository $reservationsRepository,
        private readonly RentsRepository $rentsRepository
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
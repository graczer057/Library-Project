<?php

declare(strict_types=1);

namespace App\Controller\Admin\Rents;

use App\Repository\Books\RentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReturnRentedBook extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RentRepository $rentsRepository
    ) {

    }

    #[Route('/admin/book/rent/return/{rentId}', name: 'adminReturnedRentedBook', methods: ['GET', 'POST'])]
    public function return(int $rentId): Response
    {
        $specificRent = $this->rentsRepository->findOneBy(['id' => $rentId]);

        if (!$specificRent) {
            $this->addFlash('error', 'Podanego wypożyczenia nie ma, spróbuj ponownie później');

            return $this->redirectToRoute('adminListRentedBooks');
        }

        $todayDate = new \DateTime("now");

        //if ($todayDate->getTimestamp() < $specificRent->getExpireDate()->getTimestamp()) {
            $bookQuantity = $specificRent->getBookId()->getQuantity();
            $newBookQuantity = $specificRent->getBookId()->setQuantity($bookQuantity + 1);

            $readerReservationQuantity = $specificRent->getReaderId()->getReservationsQuantity();
            $newReaderReservationQuantity = $specificRent->getReaderId()->setReservationsQuantity($readerReservationQuantity + 1);

            $user = $specificRent->getReaderId()->getUserId()->setIsBanned(false);

            $this->entityManager->remove($specificRent);
            //$this->entityManager->remove($reservation);
            $this->entityManager->persist($user);
            $this->entityManager->persist($newBookQuantity);
            $this->entityManager->persist($newReaderReservationQuantity);
            $this->entityManager->flush();

            $this->addFlash('success', 'Udało się zakończyć wypożyczenie książki. Rezerwacja została usunięta, liczba książek na stanie oraz możliwośći rezerwacji i/lub wypożyczeń czytelnika zwiększyły się o jeden');

            return $this->redirectToRoute('adminListRentedBooks');
//        } else {
//
//        }
    }
}
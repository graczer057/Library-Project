<?php

declare(strict_types=1);

namespace App\Controller\Reader;

use App\Controller\Interfaces\HomepageInterface;
use App\Entity\Users\Reader;
use App\Form\Reader\ReaderCompleteRegistrationFormType;
use App\Repository\Books\BookRepository;
use App\Repository\Books\RentRepository;
use App\Repository\Books\ReservationRepository;
use App\Repository\Users\ReaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomepageController extends AbstractController implements HomepageInterface
{
    public function __construct(
        private readonly BookRepository        $booksRepository,
        private readonly ReaderRepository      $readersRepository,
        private readonly ReservationRepository $reservationsRepository,
        private readonly RentRepository        $rentsRepository,
        private readonly EntityManagerInterface $entityManager
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

    #[Route('/reader/complete/registration', name: 'readerCompleteRegistration')]
    public function completeRegistration(UserInterface $user, Request $request): Response
    {
        $specificReader = $this->readersRepository->findOneBy(['userId' => $user->getId()]);

        if (!$specificReader) {
            $this->addFlash('error', 'Przykro nam, ale wystąpił nieoczekiwany błąd, prosimy o kontakt z biblioteką szkolną w celu weryfikacji.');

            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ReaderCompleteRegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $updateReader = $specificReader->updateReader(
                $data['name'],
                $data['surname']
            );

            $updatePassword = $user->setPassword(
                password_hash($data['password'], PASSWORD_BCRYPT)
            );

            $updateIsActive = $user->setIsActive(true);

            $this->entityManager->persist($updateReader);
            $this->entityManager->persist($updatePassword);
            $this->entityManager->flush();

            $this->addFlash('success', 'Udało się dokończyć rejestrację pomyślnie, zapraszamy do skorzystania z serwisu');

            return $this->redirectToRoute('readerHomepage');
        }

        return $this->render('Reader/Registration/completeRegistrationForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
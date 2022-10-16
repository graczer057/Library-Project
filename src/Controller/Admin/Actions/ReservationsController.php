<?php

namespace App\Controller\Admin\Actions;

use App\Controller\Admin\Interfaces\CRUDInterface;
use App\Controller\Admin\Utils\CheckValue;
use App\Controller\Admin\Utils\EntityManagerCommands;
use App\Controller\Admin\Utils\FindExistingObject\FindExistingObjects;
use App\Controller\Admin\Utils\FindObjects;
use App\Controller\Admin\Utils\FormHandler\FormHandler;
use App\Controller\Admin\Utils\ObjectsCommands\CreateObject;
use App\Controller\Admin\Utils\QuantityFactory;
use App\Form\Admin\Reservations\CreateReservationFormType;
use App\Form\Admin\Reservations\EditReservationFormType;
use App\Repository\Books\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reservation')]
class ReservationsController extends AbstractController implements CRUDInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ReservationRepository $reservationRepository,
        private readonly FormHandler $formHandler
    ) {
    }

    #[Route('/confirm/{id}', name: 'adminConfirmReservation', methods: ['GET', 'POST'])]
    public function confirm(int $id): Response
    {
        try {
             $specificReservation = FindExistingObjects::findExistingObject($this->reservationRepository, 'id', $id, true);
        } catch (Exception) {
            $this->addFlash('error', 'Przykro nam, ale podana rezerwacja nie istnieje.');

            return $this->redirectToRoute('adminListReservation');
        }

        $newRecords['rent'] = CreateObject::createRent($specificReservation->getBookId(), $specificReservation->getReaderId());
        $newRecords['reservation'] = $specificReservation->setIsRented(true);

        EntityManagerCommands::persistObjects($this->entityManager, $newRecords);

        $this->addFlash('success', 'Pomyślnie wypożyczono książkę');

        return $this->redirectToRoute('adminListReservation');
    }

    #[Route('/reservation/create', name: 'adminCreateReservation', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $form = $this->formHandler->checkForm($request, CreateReservationFormType::class);

        if ( ($form->isSubmitted()) && ($form->isValid())) {
            $data = FormHandler::getDataForm($form);

            try {
                FindExistingObjects::findExistingObjectByTwoArguments($this->reservationRepository, 'bookId', $data['bookId']->getId(), 'readerId', $data['readerId']->getId());
            } catch (Exception) {
                $this->addFlash('error', 'Przykro nam, ale rezerwacja dla podanego użytkownika oraz książki już istnieje');

                return $this->render('Admin/Reservations/createReservationForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                CheckValue::checkValue($data['bookId']->getQuantity(), 1, true);
            } catch (Exception) {
                $this->addFlash('error', 'Przepraszamy, ale podanej książki nie ma na stanie');

                return $this->render('Admin/Reservations/createReservationForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                CheckValue::checkValue($data['readerId']->getReservationsQuantity(), 1, true);
            } catch (Exception) {
                $this->addFlash('error', 'Przepraszamy, ale podany czytelnik nie ma więcej możliwości wypożyczeń');

                return $this->render('Admin/Reservations/createReservationForm.html.twig', [
                    'form' => $form->createView()
                ]);
            }


            $newReservation = QuantityFactory::changeQuantity($data['bookId'], $data['readerId'], false);

            $newReservation[] = CreateObject::createReservation($data);

            EntityManagerCommands::persistObjects($this->entityManager, $newReservation);

            $this->addFlash('success', 'Rezerwacja została pomyślnie dodana.');

            return $this->redirectToRoute('adminListUsers');
        }

        return $this->render('Admin/Reservations/createReservationForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/reservation/edit/{id}', name: 'adminEditReservation', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        try {
            $specificReservation = FindExistingObjects::findExistingObject($this->reservationRepository, 'id', $id, true);
        } catch (Exception) {
            $this->addFlash('error', 'Przykro nam, ale podana rezerwacja nie istnieje.');

            return $this->redirectToRoute('adminListReservation');
        }

        $oldBook = $specificReservation->getBookId();
        $oldReader = $specificReservation->getReaderId();

        $form = $this->formHandler->checkForm($request, EditReservationFormType::class, $specificReservation);

        if ( ($form->isSubmitted()) && ($form->isValid())) {
            try {
                CheckValue::checkValue($specificReservation->getBookId()->getQuantity(), 1, true);
            } catch (Exception) {
                $this->addFlash('error', 'Przepraszamy, ale podanej książki nie ma na stanie');

                return $this->render('Admin/Reservations/editReservation.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                CheckValue::checkValue($specificReservation->getReaderId()->getReservationsQuantity(), 1, true);
            } catch (Exception) {
                $this->addFlash('error', 'Przepraszamy, ale podany czytelnik nie ma więcej możliwości wypożyczeń');

                return $this->render('Admin/Reservations/editReservation.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $newBook = $specificReservation->getBookId();
            $newReader = $specificReservation->getReaderId();

            $resultsToChange[] = $specificReservation;

            if ($oldBook !== $newBook) {
                $resultsToChange[] = QuantityFactory::changeBookQuantity($oldBook, true);
                $resultsToChange[] = QuantityFactory::changeBookQuantity($newBook, false);
            }

            if ($oldReader !== $newReader) {
                $resultsToChange[] = QuantityFactory::changeReaderReservationQuantity($oldReader, true);
                $resultsToChange[] = QuantityFactory::changeReaderReservationQuantity($newReader, false);
            }

            EntityManagerCommands::persistObjects($this->entityManager, $resultsToChange);

            $this->addFlash('success', 'Rezerwacja została pomyślnie edytowana');

            return $this->redirectToRoute('adminListReservation');
        }

        return $this->render('Admin/Reservations/editReservation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/list', name: 'adminListReservation', methods: ['GET', 'POST'])]
    public function list(): Response
    {
        return $this->render('Admin/Reservations/listReservations.html.twig', [
            'rentedReservations' => FindObjects::findObjectsBy($this->reservationRepository, 'isRented', true, false),
            'notRentedReservations' => FindObjects::findObjectsBy($this->reservationRepository, 'isRented', false, false)
        ]);
    }

    #[Route('/cancel/{id}', name: 'adminCancelReservation', methods: ['GET', 'POST'])]
    public function delete(int $id): Response
    {
        try {
            $specificReservation = FindExistingObjects::findExistingObject($this->reservationRepository, 'id', $id, true);
        } catch (Exception) {
            $this->addFlash('error', 'Przykro nam, ale podana rezerwacja nie istnieje.');

            return $this->redirectToRoute('adminListReservation');
        }

        $quantityResults = QuantityFactory::changeQuantity($specificReservation->getBookId(), $specificReservation->getReaderId(), true);

        EntityManagerCommands::removeObject($this->entityManager, $specificReservation);
        EntityManagerCommands::persistObjects($this->entityManager, $quantityResults);

        $this->addFlash('success', 'Rezerwacja anulowana pomyślnie');

        return $this->redirectToRoute('adminListReservation');
    }
}
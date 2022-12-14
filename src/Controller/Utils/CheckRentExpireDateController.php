<?php

declare(strict_types=1);

namespace App\Controller\Utils;

use App\Repository\Books\RentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckRentExpireDateController extends AbstractController
{
    public static function check(EntityManagerInterface $entityManager, RentsRepository $rentsRepository): void
    {
        $todayDate = new \DateTime("now");

        $rentedBooks = $rentsRepository->findAll();

        foreach ($rentedBooks as $rentedBook) {
            if ($rentedBook->getExpireDate()->getTimestamp() < $todayDate->getTimestamp()) {
                $rentedBook->setIsActive(false);
                $user = $rentedBook->getReservationId()->getReaderId()->getUserId()->setIsBanned(true);
                $entityManager->persist($rentedBook);
                $entityManager->persist($user);
            }
        }

        $entityManager->flush();
    }
}
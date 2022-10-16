<?php

declare(strict_types=1);

namespace App\Controller\Utils;

use App\Repository\Books\RentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckRentExpireDateController extends AbstractController
{
    public static function check(EntityManagerInterface $entityManager, RentRepository $rentsRepository): void
    {
        $todayDate = new \DateTime("now");

        $rentedBooks = $rentsRepository->findAll();

        foreach ($rentedBooks as $rentedBook) {
            if ($rentedBook->getExpireDate()->getTimestamp() < $todayDate->getTimestamp()) {
                $user = $rentedBook->getReaderId()->getUserId()->setIsBanned(true);
                $entityManager->persist($rentedBook);
                $entityManager->persist($user);
            }
        }

        $entityManager->flush();
    }
}
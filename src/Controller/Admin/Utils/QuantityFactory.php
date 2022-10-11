<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils;

use App\Entity\Books\Book;
use App\Entity\Users\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuantityFactory extends AbstractController
{
    public static function changeQuantity(Book $book, Reader $reader, bool $isIncreasing): array
    {
        $actualBookQuantity = $book->getQuantity();
        $actualReaderReservationQuantity = $reader->getReservationsQuantity();

        if ($isIncreasing) {
            $results['bookQuantity'] = $book->setQuantity($actualBookQuantity + 1);
            $results['readerQuantity'] = $reader->setReservationsQuantity($actualReaderReservationQuantity + 1);
        } else {
            $results['bookQuantity'] = $book->setQuantity($actualBookQuantity - 1);
            $results['readerQuantity'] = $reader->setReservationsQuantity($actualReaderReservationQuantity - 1);
        }
        return $results;
    }

    public static function changeBookQuantity(Book $book, bool $isIncreasing): Book
    {
        $actualBookQuantity = $book->getQuantity();

        if ($isIncreasing) {
            $newBookQuantity = $book->setQuantity($actualBookQuantity + 1);
        } else {
            $newBookQuantity = $book->setQuantity($actualBookQuantity - 1);
        }

        return $newBookQuantity;
    }

    public static function changeReaderReservationQuantity(Reader $reader, bool $isIncreasing): Reader
    {
        $actualReaderReservationQuantity = $reader->getReservationsQuantity();

        if ($isIncreasing) {
            $newReaderReservationQuantity = $reader->setReservationsQuantity($actualReaderReservationQuantity + 1);
        } else {
            $newReaderReservationQuantity = $reader->setReservationsQuantity($actualReaderReservationQuantity - 1);
        }

        return $newReaderReservationQuantity;
    }
}
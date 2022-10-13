<?php

namespace App\DataFixtures;

use App\Entity\Books\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $book = new Book(
                'Książka'.$i,
                'Autor'.$i,
                'Opis'.$i,
                $i
            );
            $manager->persist($book);
        }
        $manager->flush();
    }
}
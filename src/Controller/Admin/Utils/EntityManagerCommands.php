<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntityManagerCommands extends AbstractController
{
    public static function persistObject(EntityManagerInterface $entityManager, mixed $object): void
    {
        $entityManager->persist($object);
        $entityManager->flush();
    }

    public static function removeObject(EntityManagerInterface $entityManager, mixed $object): void
    {
        $entityManager->remove($object);
        $entityManager->flush();
    }

    public static function persistObjects(EntityManagerInterface $entityManager, array $objects): void
    {
        foreach ($objects as $object) {
            $entityManager->persist($object);
        }
        $entityManager->flush();
    }
}
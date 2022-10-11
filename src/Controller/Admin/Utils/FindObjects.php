<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils;

use App\Entity\Users\User;
use App\Repository\Users\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FindObjects extends AbstractController
{
    public static function findAllObjects(mixed $repository): array
    {
        return $repository->findAll();
    }

    public static function findObjectsBy(mixed $repository, string $valueName, mixed $value, bool $isReturnOneObject): mixed
    {
        switch ($isReturnOneObject) {
            case true:
                return $repository->findOneBy([$valueName => $value]);
            case false:
                return $repository->findBy([$valueName => $value]);
        }
    }

    /*public static function findUserByEmail(UserRepository $userRepository, string $email): ?User
    {
        return $userRepository->findOneBy(['email' => $email]);
    }

    public static function findUserByLogin(UserRepository $userRepository, string $login): ?User
    {
        return $userRepository->findOneBy(['login' => $login]);
    }

    public static function findObjectById(mixed $userRepository, int $id): mixed
    {
        return $userRepository->findOneBy(['id' => $id]);
    }

    public static function findObjectByName(mixed $bookRepository, string $name): mixed
    {
        return $bookRepository->findOneBy(['name' => $name]);
    }*/
}
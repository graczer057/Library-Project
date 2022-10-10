<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils\FindExistingObject;

use App\Controller\Admin\Utils\FindObjects;
use App\Repository\Users\UserRepository;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FindExistingObjects extends AbstractController
{
    public static function findExistingUser(UserRepository $userRepository, string $email, string $login): void
    {
        if(FindObjects::findUserByEmail($userRepository, $email) || FindObjects::findUserByLogin($userRepository, $login)) {
            throw new Exception();
        }
    }

    public static function findExistingObject(mixed $isExisting): void
    {
        if(!$isExisting) {
            throw new Exception();
        }

    }
}
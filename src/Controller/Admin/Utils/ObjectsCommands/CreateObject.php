<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils\ObjectsCommands;

use App\Entity\Books\Book;
use App\Entity\Users\User;
use App\Repository\Users\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateObject extends AbstractController
{
    public static function createUser(array $data): User
    {
        return new User(
            $data['email'],
            $data['login'],
            $data['password'],
            $data['roles']
        );
    }

    public static function createBook(array $data): Book
    {
        return new Book(
            $data['name'],
            $data['author'],
            $data['description'],
            $data['quantity']
        );
    }
}
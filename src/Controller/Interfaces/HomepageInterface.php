<?php

declare(strict_types=1);

namespace App\Controller\Interfaces;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

interface HomepageInterface
{
    public function homepage(UserInterface $user): Response;
}
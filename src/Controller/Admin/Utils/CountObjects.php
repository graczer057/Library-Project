<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountObjects extends AbstractController
{
    public static function count(array $objectArray): int
    {
        return count($objectArray);
    }
}
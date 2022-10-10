<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils;

use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckValue extends AbstractController
{
    public static function checkGreaterValue(int $userValue, int $correctValue): void
    {
        if ($userValue < 0) {
            throw new Exception();
        }
    }
}
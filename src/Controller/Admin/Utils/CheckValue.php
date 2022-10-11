<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils;

use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckValue extends AbstractController
{
    public static function checkValue(int $userValue, int $correctValue, bool $isGreater): void
    {
        if ($isGreater) {
            if ($userValue < $correctValue) {
                throw new Exception();
            }
        } else {
            if ($userValue > $correctValue) {
                throw new Exception();
            }
        }
    }
}
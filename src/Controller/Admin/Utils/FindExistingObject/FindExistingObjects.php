<?php

declare(strict_types=1);

namespace App\Controller\Admin\Utils\FindExistingObject;

use App\Controller\Admin\Utils\FindObjects;
use App\Repository\Users\UserRepository;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FindExistingObjects extends AbstractController
{
    public static function findExistingObjectByTwoArguments(mixed $repository, string $firstValueName, mixed $firstValue, string $secondValueName, string $secondValue): void
    {
        if(FindObjects::findObjectsBy($repository, $firstValueName, $firstValue, true) && FindObjects::findObjectsBy($repository, $secondValueName, $secondValue, true)) {
            throw new Exception();
        }
    }

    public static function findExistingObject(mixed $repository, string $valueName, mixed $value, bool $isExisting): mixed
    {
        $specificObject = FindObjects::findObjectsBy($repository, $valueName, $value, true);

        if($isExisting) {
            if(!$specificObject) {
                throw new Exception();
            }
        } else {
            if($specificObject) {
                throw new Exception();
            }
        }

        return $specificObject;

    }
}
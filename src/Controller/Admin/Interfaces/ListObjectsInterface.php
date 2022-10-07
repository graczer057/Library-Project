<?php

declare(strict_types=1);

namespace App\Controller\Admin\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface ListObjectsInterface
{
    public function list(): Response;
}
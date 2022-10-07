<?php

declare(strict_types=1);

namespace App\Controller\Admin\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface DeleteObjectInterface
{
    public function delete(int $id): Response;
}
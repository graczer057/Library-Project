<?php

declare(strict_types=1);

namespace App\Controller\Admin\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CreateObjectInterface
{
    public function create(Request $request): Response;
}
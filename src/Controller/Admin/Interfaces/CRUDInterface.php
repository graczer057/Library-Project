<?php

declare(strict_types=1);

namespace App\Controller\Admin\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CRUDInterface
{
    public function create(Request $request): Response;
    public function edit(int $id, Request $request): Response;
    public function list(): Response;
    public function delete(int $id): Response;
}
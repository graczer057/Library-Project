<?php

declare(strict_types=1);

namespace App\Controller\Utils;

use App\Repository\Books\RentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class RoleHandlerController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RentRepository $rentsRepository
    ) {

    }

    /**
     * @throws Exception
     */
    #[Route("/Role/Handler", name: "RoleHandler")]
    public function chooseHomepage(UserInterface $user): Response
    {
        CheckRentExpireDateController::check($this->entityManager, $this->rentsRepository);

        if ($user->getRoles() === ["ROLE_ADMIN"]) {
            return $this->redirectToRoute('adminHomepage');
        } elseif ($user->getRoles() === ["ROLE_READER"]) {
            return $this->redirectToRoute('readerHomepage');
        } else {
            throw new Exception();
        }
    }
}
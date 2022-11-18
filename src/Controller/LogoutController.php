<?php

declare(strict_types=1);

namespace App\Controller;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'logout')]
    public function index(): Response
    {
        throw new RuntimeException('Logout not configured');
    }
}

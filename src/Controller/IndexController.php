<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(
        Connection $connection
    ): JsonResponse {
        $version = $connection->fetchOne('SELECT @@version');

        return new JsonResponse('Hello World - mysql: '.$version);
    }
}

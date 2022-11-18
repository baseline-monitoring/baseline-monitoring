<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\Read\BaselineConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(BaselineConfigurationRepository $baselineConfigurationRepository): Response
    {
        return $this->render('baseline/index.html.twig', [
            'baselines' => $baselineConfigurationRepository->findAll(),
        ]);
    }
}

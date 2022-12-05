<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BaselineConfiguration;
use App\Repository\Read\BaselineErrorsRepository;
use App\Service\ChartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('base/', name: 'baseline_')]
class BaselineController extends AbstractController
{
    #[Route('{id}', name: 'chart')]
    public function index(
        BaselineConfiguration $baselineConfiguration,
        ChartService $chartService
    ): Response {
        $latestResult = $baselineConfiguration->getBaselineStatisticResults()->last();

        return $this->render('baseline/show.html.twig', [
            'baselineConfiguration' => $baselineConfiguration,
            'chart' => $chartService->getChart($baselineConfiguration),
            'latestResult' => $latestResult,
        ]);
    }

    #[Route('{id}/errors', name: 'errors')]
    public function errorList(
        BaselineConfiguration $baselineConfiguration,
        BaselineErrorsRepository $baselineErrorsRepository
    ): Response {
        return $this->render('baseline/error_list.html.twig', [
            'baselineConfiguration' => $baselineConfiguration,
            'errors' => $baselineErrorsRepository->findBy([], ['count' => 'DESC']),
        ]);
    }
}

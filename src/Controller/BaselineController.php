<?php

namespace App\Controller;

use App\Entity\BaselineConfiguration;
use App\Repository\Read\BaselineStatisticResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('base/', name: 'baseline_')]
class BaselineController extends AbstractController
{
    #[Route('{id}', name: 'chart')]
    public function index(
        BaselineConfiguration $baselineConfiguration,
        ChartBuilderInterface $chartBuilder,
        BaselineStatisticResultRepository $baselineStatisticResultRepository
    ): Response {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chartData = $baselineStatisticResultRepository->getChartDataForBaselineConfiguration($baselineConfiguration);

        $days = array_column($chartData, 'commit_date_day');
        $chart->setData([
            'labels' => $days,
            'datasets' => [
                [
                    'label' => 'Errors',
                    'backgroundColor' => 'rgb(242, 90, 15)',
                    'borderColor' => 'rgb(242, 90, 15)',
                    'data' => array_column($chartData, 'commutative_errors'),
                ],
                [
                    'label' => 'Unique',
                    'backgroundColor' => 'rgb(15, 129, 242)',
                    'borderColor' => 'rgb(15, 129, 242)',
                    'data' => array_column($chartData, 'unique_errors'),
                ],
            ],
        ]);

        $chart->setOptions([
            'type' => 'line',
            'borderWidth' => 1.5,
            'radius' => 1.5,
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        $latestResult = $baselineConfiguration->getBaselineStatisticResults()->last();

        return $this->render('baseline/show.html.twig', [
            'baselineConfiguration' => $baselineConfiguration,
            'chart' => $chart,
            'latestResult' => $latestResult,
        ]);
    }

    #[Route('{id}/goals', name: 'goals')]
    public function goals(
        BaselineConfiguration $baselineConfiguration
    ): Response {
        $latestResult = $baselineConfiguration->getBaselineStatisticResults()->last();

        return $this->render('baseline/goals.html.twig', [
            'baselineConfiguration' => $baselineConfiguration,
            'latestResult' => $latestResult,
        ]);
    }
}

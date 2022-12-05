<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\BaselineConfiguration;
use App\Repository\Read\BaselineStatisticResultRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{
    public function __construct(
        private readonly ChartBuilderInterface $chartBuilder,
        private readonly BaselineStatisticResultRepository $baselineStatisticResultRepository
    ) {
    }

    public function getChart(BaselineConfiguration $baselineConfiguration): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chartData = $this->baselineStatisticResultRepository->getChartDataForBaselineConfiguration($baselineConfiguration);

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

        return $chart;
    }
}

<?php

declare(strict_types=1);

namespace App\Dto;

final class BaselineStatisticResultCollection
{
    /**
     * @var BaselineStatisticResult[]
     */
    private array $statisticResults = [];

    /**
     * @param BaselineStatisticResult[] $statisticResults
     */
    public function __construct(array $statisticResults = [])
    {
        $this->setStatisticResults($statisticResults);
    }

    /**
     * @return BaselineStatisticResult[]
     */
    public function getStatisticResults(): array
    {
        return $this->statisticResults;
    }

    /**
     * @param BaselineStatisticResult[] $statisticResults
     */
    public function setStatisticResults(array $statisticResults): BaselineStatisticResultCollection
    {
        $this->statisticResults = [];

        foreach ($statisticResults as $statisticResult) {
            $this->addStatisticResult($statisticResult);
        }

        return $this;
    }

    public function addStatisticResult(BaselineStatisticResult $statisticResult): BaselineStatisticResultCollection
    {
        $this->statisticResults[] = $statisticResult;

        return $this;
    }
}

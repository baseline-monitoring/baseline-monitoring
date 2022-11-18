<?php

namespace App\Repository\Write;

use App\Entity\BaselineStatisticResult;
use Doctrine\ORM\EntityManagerInterface;

class BaselineStatisticResultManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function save(BaselineStatisticResult $baselineStatisticResult): void
    {
        $this->entityManager->persist($baselineStatisticResult);
        $this->entityManager->flush();
    }
}

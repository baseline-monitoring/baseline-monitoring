<?php

declare(strict_types=1);

namespace App\Repository\Write;

use App\Entity\BaselineConfiguration;
use App\Entity\BaselineErrors;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class BaselineErrorsManager
{
    public const BATCH_SIZE = 50;

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly Connection $connection)
    {
    }

    public function addError(BaselineErrors $error): void
    {
        $this->entityManager->persist($error);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function deleteErrorsForBaselineConfiguration(BaselineConfiguration $baselineConfiguration): void
    {
        try {
            $this->connection->executeStatement('DELETE FROM baseline_errors WHERE baseline_configuration_id = :baselineConfigurationId', [
                'baselineConfigurationId' => $baselineConfiguration->getId(),
            ]);
        } catch (Exception) {
            // Ignore this error
        }
    }
}

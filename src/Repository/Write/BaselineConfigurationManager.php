<?php

declare(strict_types=1);

namespace App\Repository\Write;

use App\Entity\BaselineConfiguration;
use Doctrine\ORM\EntityManagerInterface;

class BaselineConfigurationManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function persist(BaselineConfiguration $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(BaselineConfiguration $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}

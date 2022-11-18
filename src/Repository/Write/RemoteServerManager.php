<?php

declare(strict_types=1);

namespace App\Repository\Write;

use App\Entity\RemoteServer;
use Doctrine\ORM\EntityManagerInterface;

class RemoteServerManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function persist(RemoteServer $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(RemoteServer $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}

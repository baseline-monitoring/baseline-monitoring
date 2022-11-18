<?php

declare(strict_types=1);

namespace App\Repository\Read;

use App\Entity\RemoteServer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RemoteServer>
 *
 * @method RemoteServer|null find($id, $lockMode = null, $lockVersion = null)
 * @method RemoteServer|null findOneBy(array $criteria, array $orderBy = null)
 * @method RemoteServer[]    findAll()
 * @method RemoteServer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RemoteServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RemoteServer::class);
    }
}

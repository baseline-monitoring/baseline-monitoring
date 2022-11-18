<?php

declare(strict_types=1);

namespace App\Repository\Read;

use App\Entity\BaselineConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BaselineConfiguration>
 *
 * @method BaselineConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaselineConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaselineConfiguration[]    findAll()
 * @method BaselineConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaselineConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaselineConfiguration::class);
    }
}

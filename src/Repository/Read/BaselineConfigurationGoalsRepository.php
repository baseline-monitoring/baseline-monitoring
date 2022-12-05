<?php

declare(strict_types=1);

namespace App\Repository\Read;

use App\Entity\BaselineConfigurationGoals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BaselineConfigurationGoals>
 *
 * @method BaselineConfigurationGoals|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaselineConfigurationGoals|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaselineConfigurationGoals[]    findAll()
 * @method BaselineConfigurationGoals[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaselineConfigurationGoalsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaselineConfigurationGoals::class);
    }
}

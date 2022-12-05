<?php

declare(strict_types=1);

namespace App\Repository\Read;

use App\Entity\BaselineErrors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BaselineErrors>
 *
 * @method BaselineErrors|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaselineErrors|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaselineErrors[]    findAll()
 * @method BaselineErrors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaselineErrorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaselineErrors::class);
    }
}

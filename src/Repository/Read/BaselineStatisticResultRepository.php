<?php

namespace App\Repository\Read;

use App\Entity\BaselineConfiguration;
use App\Entity\BaselineStatisticResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BaselineStatisticResult>
 *
 * @method BaselineStatisticResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaselineStatisticResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaselineStatisticResult[]    findAll()
 * @method BaselineStatisticResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaselineStatisticResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaselineStatisticResult::class);
    }

    /**
     * @return array{commutative_errors: int, unique_errors: int, commit_date_day: string}
     *
     * @throws Exception
     */
    public function getChartDataForBaselineConfiguration(BaselineConfiguration $baselineConfiguration): array
    {
        $query = <<<SQL
SELECT
    z.commutative_errors,
    z.unique_errors,
    z.commit_date_day
FROM (
    SELECT
        ROW_NUMBER() OVER (PARTITION BY DATE(b.commit_date) ORDER by b.commutative_errors) AS 'rank',
        b.commutative_errors,
        b.unique_errors,
        DATE(b.commit_date) AS 'commit_date_day'
    FROM baseline_statistic_result b
    WHERE
        b.baseline_configuration_id = :baselineConfigurationId
        AND b.commit_date > DATE_SUB(NOW(), INTERVAL 1 YEAR)
) AS z
WHERE z.rank = 1
SQL;

        /** @var array{commutative_errors: int, unique_errors: int, commit_date_day: string} $result */
        $result = $this->_em->getConnection()->fetchAllAssociative($query, [
            'baselineConfigurationId' => $baselineConfiguration->getId(),
        ]);

        return $result;
    }
}

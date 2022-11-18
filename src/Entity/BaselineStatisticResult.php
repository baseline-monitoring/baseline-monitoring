<?php

namespace App\Entity;

use App\Repository\Read\BaselineStatisticResultRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BaselineStatisticResultRepository::class)]
#[ORM\Index(fields: ['baselineConfiguration', 'commitHash'])]
class BaselineStatisticResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id = 0;

    #[ORM\ManyToOne(inversedBy: 'baselineStatisticResults')]
    #[ORM\JoinColumn(nullable: false)]
    private BaselineConfiguration $baselineConfiguration;

    #[ORM\Column]
    private int $commutativeErrors;

    #[ORM\Column]
    private int $uniqueErrors;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $toolVersion;

    #[ORM\Column(length: 255)]
    private string $commitHash;

    #[ORM\Column]
    private DateTimeImmutable $commitDate;

    #[ORM\Column]
    private DateTimeImmutable $created;

    public function __construct(
        BaselineConfiguration $baselineConfiguration,
        int $commutativeErrors,
        int $uniqueErrors,
        string $commitHash,
        DateTimeImmutable $commitDate,
        ?string $toolVersion = null
    ) {
        $this->baselineConfiguration = $baselineConfiguration;
        $this->commutativeErrors = $commutativeErrors;
        $this->uniqueErrors = $uniqueErrors;
        $this->commitHash = $commitHash;
        $this->commitDate = $commitDate;
        $this->toolVersion = $toolVersion;
        $this->created = new DateTimeImmutable(timezone: new DateTimeZone('UTC'));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): BaselineStatisticResult
    {
        $this->id = $id;

        return $this;
    }

    public function getBaselineConfiguration(): BaselineConfiguration
    {
        return $this->baselineConfiguration;
    }

    public function setBaselineConfiguration(BaselineConfiguration $baselineConfiguration): BaselineStatisticResult
    {
        $this->baselineConfiguration = $baselineConfiguration;

        return $this;
    }

    public function getCommutativeErrors(): int
    {
        return $this->commutativeErrors;
    }

    public function setCommutativeErrors(int $commutativeErrors): BaselineStatisticResult
    {
        $this->commutativeErrors = $commutativeErrors;

        return $this;
    }

    public function getUniqueErrors(): int
    {
        return $this->uniqueErrors;
    }

    public function setUniqueErrors(int $uniqueErrors): BaselineStatisticResult
    {
        $this->uniqueErrors = $uniqueErrors;

        return $this;
    }

    public function getToolVersion(): ?string
    {
        return $this->toolVersion;
    }

    public function setToolVersion(?string $toolVersion): BaselineStatisticResult
    {
        $this->toolVersion = $toolVersion;

        return $this;
    }

    public function getCommitHash(): string
    {
        return $this->commitHash;
    }

    public function setCommitHash(string $commitHash): BaselineStatisticResult
    {
        $this->commitHash = $commitHash;

        return $this;
    }

    public function getCommitDate(): DateTimeImmutable
    {
        return $this->commitDate;
    }

    public function setCommitDate(DateTimeImmutable $commitDate): BaselineStatisticResult
    {
        $this->commitDate = $commitDate;

        return $this;
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(DateTimeImmutable $created): BaselineStatisticResult
    {
        $this->created = $created;

        return $this;
    }
}

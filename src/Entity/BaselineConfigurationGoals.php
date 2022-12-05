<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\Read\BaselineConfigurationGoalsRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BaselineConfigurationGoalsRepository::class)]
class BaselineConfigurationGoals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = 0;

    #[ORM\Column]
    private int $errorGoal = 0;

    #[ORM\Column(length: 255)]
    private string $benefitTitle = '';

    #[ORM\Column(type: Types::TEXT)]
    private string $benefitDescription = '';

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'baselineConfigurationGoals')]
    #[ORM\JoinColumn(nullable: false)]
    private BaselineConfiguration $baselineConfiguration;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable(timezone: new DateTimeZone('UTC'));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): BaselineConfigurationGoals
    {
        $this->id = $id;

        return $this;
    }

    public function getErrorGoal(): int
    {
        return $this->errorGoal;
    }

    public function setErrorGoal(int $errorGoal): BaselineConfigurationGoals
    {
        $this->errorGoal = $errorGoal;

        return $this;
    }

    public function getBenefitTitle(): string
    {
        return $this->benefitTitle;
    }

    public function setBenefitTitle(string $benefitTitle): BaselineConfigurationGoals
    {
        $this->benefitTitle = $benefitTitle;

        return $this;
    }

    public function getBenefitDescription(): string
    {
        return $this->benefitDescription;
    }

    public function setBenefitDescription(string $benefitDescription): BaselineConfigurationGoals
    {
        $this->benefitDescription = $benefitDescription;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): BaselineConfigurationGoals
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBaselineConfiguration(): BaselineConfiguration
    {
        return $this->baselineConfiguration;
    }

    public function setBaselineConfiguration(BaselineConfiguration $baselineConfiguration): BaselineConfigurationGoals
    {
        $this->baselineConfiguration = $baselineConfiguration;

        return $this;
    }
}

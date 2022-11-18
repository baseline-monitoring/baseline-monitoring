<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\Read\BaselineConfigurationRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BaselineConfigurationRepository::class)]
class BaselineConfiguration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = 0;

    #[ORM\Column(type: 'text')]
    private string $repositoryUrl = '';

    #[ORM\Column(type: 'string', length: 255)]
    private string $name = '';

    #[ORM\Column(type: 'string', length: 255)]
    private string $pathToConfiguration = '';

    #[ORM\Column(type: 'string', length: 255)]
    private string $pathToBaseline = '';

    #[ORM\Column(type: 'string', length: 255, options: ['default' => 'main'])]
    private string $mainBranch = 'main';

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private RemoteServer $remoteServer;

    #[ORM\OneToMany(mappedBy: 'baselineConfiguration', targetEntity: BaselineConfigurationGoals::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $baselineConfigurationGoals;

    #[ORM\OneToMany(mappedBy: 'baselineConfiguration', targetEntity: BaselineStatisticResult::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $baselineStatisticResults;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable(timezone: new DateTimeZone('UTC'));
        $this->baselineConfigurationGoals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRepositoryUrl(): string
    {
        return $this->repositoryUrl;
    }

    public function setRepositoryUrl(string $repositoryUrl): BaselineConfiguration
    {
        $this->repositoryUrl = $repositoryUrl;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): BaselineConfiguration
    {
        $this->name = $name;

        return $this;
    }

    public function getPathToConfiguration(): string
    {
        return $this->pathToConfiguration;
    }

    public function setPathToConfiguration(string $pathToConfiguration): BaselineConfiguration
    {
        $this->pathToConfiguration = $pathToConfiguration;

        return $this;
    }

    public function getPathToBaseline(): string
    {
        return $this->pathToBaseline;
    }

    public function setPathToBaseline(string $pathToBaseline): BaselineConfiguration
    {
        $this->pathToBaseline = $pathToBaseline;

        return $this;
    }

    public function getMainBranch(): string
    {
        return $this->mainBranch;
    }

    public function setMainBranch(string $mainBranch): BaselineConfiguration
    {
        $this->mainBranch = $mainBranch;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): BaselineConfiguration
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRemoteServer(): RemoteServer
    {
        return $this->remoteServer;
    }

    public function setRemoteServer(RemoteServer $remoteServer): BaselineConfiguration
    {
        $this->remoteServer = $remoteServer;

        return $this;
    }

    /**
     * @return Collection<int, BaselineConfigurationGoals>
     */
    public function getBaselineConfigurationGoals(): Collection
    {
        return $this->baselineConfigurationGoals;
    }

    public function addBaselineConfigurationGoal(BaselineConfigurationGoals $baselineConfigurationGoal): self
    {
        if (!$this->baselineConfigurationGoals->contains($baselineConfigurationGoal)) {
            $this->baselineConfigurationGoals->add($baselineConfigurationGoal);
            $baselineConfigurationGoal->setBaselineConfiguration($this);
        }

        return $this;
    }

    public function removeBaselineConfigurationGoal(BaselineConfigurationGoals $baselineConfigurationGoal): self
    {
        $this->baselineConfigurationGoals->removeElement($baselineConfigurationGoal);

        return $this;
    }

    /**
     * @return Collection<int, BaselineStatisticResult>
     */
    public function getBaselineStatisticResults(): Collection
    {
        return $this->baselineStatisticResults;
    }

    public function setBaselineStatisticResults(Collection $baselineStatisticResults): BaselineConfiguration
    {
        $this->baselineStatisticResults = $baselineStatisticResults;

        return $this;
    }
}

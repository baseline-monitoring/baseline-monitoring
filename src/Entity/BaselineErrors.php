<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\Read\BaselineErrorsRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BaselineErrorsRepository::class)]
class BaselineErrors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private BaselineConfiguration $baselineConfiguration;

    #[ORM\Column(type: Types::TEXT)]
    private string $message;

    #[ORM\Column]
    private int $count;

    #[ORM\Column(type: Types::TEXT)]
    private string $path;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function __construct(
        BaselineConfiguration $baselineConfiguration,
        string $message,
        int $count,
        string $path
    ) {
        $this->baselineConfiguration = $baselineConfiguration;
        $this->message = $message;
        $this->count = $count;
        $this->path = $path;
        $this->createdAt = new DateTimeImmutable(timezone: new DateTimeZone('UTC'));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): BaselineErrors
    {
        $this->id = $id;

        return $this;
    }

    public function getBaselineConfiguration(): BaselineConfiguration
    {
        return $this->baselineConfiguration;
    }

    public function setBaselineConfiguration(BaselineConfiguration $baselineConfiguration): BaselineErrors
    {
        $this->baselineConfiguration = $baselineConfiguration;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): BaselineErrors
    {
        $this->message = $message;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): BaselineErrors
    {
        $this->count = $count;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): BaselineErrors
    {
        $this->path = $path;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): BaselineErrors
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

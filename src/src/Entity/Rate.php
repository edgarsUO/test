<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\RateRepository;
use DateTimeInterface;
use Decimal\Decimal;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RateRepository::class)]
class Rate
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 3)]
    private string $base;

    #[ORM\Column(length: 3, unique: true)]
    private string $currency;

    #[ORM\Column(type: 'php_decimal', precision: 32, scale: 16, nullable: false)]
    private Decimal $value;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $timestamp;

    public function __construct(
        string $base,
        string $currency,
        Decimal $value,
        DateTimeInterface $timestamp
    ) {
        $this->base = $base;
        $this->value = $value;
        $this->currency = $currency;
        $this->timestamp = $timestamp;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function setBase(string $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getValue(): Decimal
    {
        return $this->value;
    }

    public function setValue(Decimal $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}

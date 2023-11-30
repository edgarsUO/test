<?php declare(strict_types=1);

namespace App\DTO\RatesResponse;

use App\Validator\IsBaseCurrency\IsBaseCurrency;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Response
{
    #[Assert\NotBlank]
    #[Assert\IsTrue]
    public ?bool $success;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\Length(exactly: 10)]
    public ?int $timestamp;

    #[Assert\NotBlank]
    #[Assert\Currency]
    #[IsBaseCurrency]
    public ?string $base;

    #[Assert\NotBlank]
    #[Assert\Date]
    public ?string $date;

    #[Assert\NotBlank]
    #[Assert\Valid]
    #[Assert\All(
        new Assert\Type(CurrencyRate::class)
    )]
    public ?array $rates;

    public function timestamp(): DateTimeInterface
    {
        return (new DateTimeImmutable())->setTimestamp($this->timestamp);
    }

    public function base(): string
    {
        return $this->base;
    }

    /**
     * @return CurrencyRate[]
     */
    public function rates(): array
    {
        return $this->rates;
    }
}

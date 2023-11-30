<?php declare(strict_types=1);

namespace App\DTO\RatesResponse;

use Symfony\Component\Validator\Constraints as Assert;
use Decimal\Decimal;

final class CurrencyRate
{
    // Assert\Currency fails on multiple entries returned by rates api
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 3)]
    public ?string $currency;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public ?Decimal $value;
}

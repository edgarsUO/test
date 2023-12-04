<?php declare(strict_types=1);

namespace App\DTO;

use App\Enum\CurrencyEnum;
use Decimal\Decimal;

final readonly class ConversionResult
{
    public function __construct(
        public CurrencyEnum $deductionCurrency,
        public Decimal $deductionAmount,
        public CurrencyEnum $additionCurrency,
        public Decimal $additionAmount
    ) {
    }
}

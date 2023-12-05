<?php declare(strict_types=1);

namespace App\Model;

use App\Enum\CurrencyEnum;
use Decimal\Decimal;

final readonly class ConversionResult
{
    public function __construct(
        private CurrencyEnum $deductionCurrency,
        private Decimal $deductionAmount,
        private CurrencyEnum $additionCurrency,
        private Decimal $additionAmount
    ) {
    }

    public function deductionCurrency(): CurrencyEnum
    {
        return $this->deductionCurrency;
    }

    public function deductionAmount(): Decimal
    {
        return $this->deductionAmount;
    }

    public function additionCurrency(): CurrencyEnum
    {
        return $this->additionCurrency;
    }

    public function additionAmount(): Decimal
    {
        return $this->additionAmount;
    }
}

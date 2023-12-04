<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\ConversionResult;
use App\Enum\CurrencyEnum;
use App\Repository\RateRepository;
use Decimal\Decimal;

final readonly class ConversionService
{
    public function __construct(private RateRepository $rates)
    {
    }

    public function convert(CurrencyEnum $from, CurrencyEnum $to, Decimal $amount): ConversionResult
    {
        $fromRate = $this->rates->byCurrency($from->value);
        $toRate = $this->rates->byCurrency($to->value);

        $conversionRate = $fromRate->getValue()->div($toRate->getValue());
        $deductionAmount = $amount->mul($conversionRate);

        return new ConversionResult($from, $deductionAmount, $to, $amount);
    }
}

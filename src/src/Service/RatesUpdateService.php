<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Rate;
use App\HttpClient\CurrencyRatesClient;
use App\Parser\CurrencyRatesParser;
use App\Repository\RateRepository;

final readonly class RatesUpdateService
{
    /** @codeCoverageIgnore  */
    public function __construct(
        private RateRepository $rateRepository,
        private CurrencyRatesClient $currencyRatesClient,
        private CurrencyRatesParser $currencyRatesParser
    ) {
    }

    public function updateRates(): void
    {
        $ratesResponse = $this->currencyRatesClient->getRates();
        $ratesObject = $this->currencyRatesParser->parse($ratesResponse);

        $baseCurrency = $ratesObject->base();
        $timestamp = $ratesObject->timestamp();
        $rates = $ratesObject->rates();

        foreach ($rates as $rate) {
            $currency = $rate->currency;
            $rateValue = $rate->value;

            $existingRate = $this->rateRepository->byCurrency($currency);

            if (null === $existingRate) {
                $rate = new Rate(
                    $baseCurrency,
                    $currency,
                    $rateValue,
                    $timestamp
                );

                $this->rateRepository->add($rate);
            } else {
                $existingRate
                    ->setBase($baseCurrency)
                    ->setValue($rateValue)
                    ->setTimestamp($timestamp);

                $this->rateRepository->update($existingRate);
            }
        }
    }
}

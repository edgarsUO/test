<?php declare(strict_types=1);

namespace App\DTO\RatesResponse;

use Decimal\Decimal;

final class Factory
{
    public static function create(array $data): Response
    {
        $response = new Response();

        $response->success = $data['success'] ?? null;
        $response->timestamp = $data['timestamp'] ?? null;
        $response->base = $data['base'] ?? null;
        $response->date = $data['date'] ?? null;

        foreach ($data['rates'] ?? [] as $currency => $value) {
            $rate = new CurrencyRate();

            $rate->currency = $currency;
            $rate->value = new Decimal((string) $value);

            $response->rates[] = $rate;
        }

        return $response;
    }
}

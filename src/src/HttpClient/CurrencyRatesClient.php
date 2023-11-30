<?php declare(strict_types=1);

namespace App\HttpClient;

use App\Enum\CurrencyEnum;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CurrencyRatesClient
{
    private const RATES_BASE_URI = 'https://api.apilayer.com/';
    private const RATES_LATEST_URI = 'exchangerates_data/latest?base=%s';
    private const RATES_API_KEY_HEADER_NAME = 'apikey';

    /** @codeCoverageIgnore  */
    public function __construct(
        private HttpClientInterface $client,
        private readonly CurrencyEnum $baseCurrency,
        readonly string $ratesApiKey
    ) {
        $this->client = $client->withOptions([
            'base_uri' => self::RATES_BASE_URI,
            'headers' => [
                self::RATES_API_KEY_HEADER_NAME => $ratesApiKey
            ]
        ]);
    }

    public function getRates(): array
    {
        $response = $this->client->request(
            Request::METHOD_GET,
            sprintf(self::RATES_LATEST_URI, $this->baseCurrency->value)
        );

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new ClientException($response);
        }

        return $response->toArray();
    }
}

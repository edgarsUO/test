<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Enum\CurrencyEnum;
use App\HttpClient\CurrencyRatesClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;

final class CurrencyRatesClientTest extends TestCase
{
    private static function ratesClient(array $responses): CurrencyRatesClient
    {
        return new CurrencyRatesClient(
            new MockHttpClient($responses),
            CurrencyEnum::EUR,
            ''
        );
    }

    /** @test */
    public function itThrowsExceptionIfResponseCodeIsNot200(): void
    {
        $response = new MockResponse('', ['http_code' => 400]);
        $ratesClient = self::ratesClient([$response]);

        $this->expectException(ClientException::class);
        $ratesClient->getRates();
    }

    /** @test */
    public function itThrowsExceptionIfResponseBodyIsEmpty(): void
    {
        $response = new MockResponse('', ['http_code' => 200]);
        $ratesClient = self::ratesClient([$response]);

        $this->expectException(DecodingExceptionInterface::class);
        $ratesClient->getRates();
    }

    /** @test */
    public function itReturnsDataArray(): void
    {
        $responseData = ['test' => 'data'];
        $response = new MockResponse(json_encode($responseData), ['http_code' => 200]);
        $ratesClient = self::ratesClient([$response]);

        $data = $ratesClient->getRates();
        self::assertEquals($responseData, $data);
    }
}

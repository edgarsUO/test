<?php declare(strict_types=1);

namespace App\Tests\Functional;

use App\DTO\RatesResponse\Response;
use App\DTO\RatesResponse\Factory;
use App\HttpClient\CurrencyRatesClient;
use App\Parser\CurrencyRatesParser;
use App\Repository\RateRepository;
use App\Service\RatesUpdateService;
use DateTimeImmutable;
use Decimal\Decimal;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class RatesUpdateServiceTest extends KernelTestCase
{
    private RateRepository $rateRepository;
    private CurrencyRatesParser $currencyRatesParser;
    private RatesUpdateService $ratesUpdateService;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        /** @var RateRepository $rateRepository */
        $rateRepository = $kernel->getContainer()->get(RateRepository::class);
        $this->rateRepository = $rateRepository;

        $currencyRatesClient = $this->createMock(CurrencyRatesClient::class);
        $this->currencyRatesParser = $this->createMock(CurrencyRatesParser::class);

        $currencyRatesClient
            ->expects($this->once())
            ->method('getRates')
            ->willReturn([]);

        $this->ratesUpdateService = new RatesUpdateService(
            $this->rateRepository,
            $currencyRatesClient,
            $this->currencyRatesParser
        );

        parent::setUp();
    }

    /** @test */
    public function itSavesRates(): void
    {
        $timestamp = 1234567890;
        $base = 'USD';
        $rate = new Decimal(1);

        $this->currencyRatesParser
            ->expects($this->once())
            ->method('parse')
            ->willReturn(self::getRatesObject($timestamp, $base, $rate));

        $this->ratesUpdateService->updateRates();
        $rates = $this->rateRepository->findAll();
        $rateTimestamp = (new DateTimeImmutable())->setTimestamp($timestamp);

        self::assertCount(2, $rates);

        self::assertEquals($rateTimestamp, $rates[0]->getTimestamp());
        self::assertEquals($base, $rates[0]->getBase());
        self::assertEquals(new Decimal('.9'), $rates[0]->getValue());

        self::assertEquals($rateTimestamp, $rates[1]->getTimestamp());
        self::assertEquals($base, $rates[1]->getBase());
        self::assertEquals(new Decimal('.8'), $rates[1]->getValue());
    }

    /** @test */
    public function itUpdatesRates(): void
    {
        $timestamp = 1345678901;
        $base = 'EUR';
        $rate = new Decimal('.9');

        $this->currencyRatesParser
            ->expects($this->once())
            ->method('parse')
            ->willReturn(self::getRatesObject($timestamp, $base, $rate));

        $this->ratesUpdateService->updateRates();
        $rates = $this->rateRepository->findAll();
        $rateTimestamp = (new DateTimeImmutable())->setTimestamp($timestamp);

        self::assertCount(2, $rates);

        self::assertEquals($rateTimestamp, $rates[0]->getTimestamp());
        self::assertEquals($base, $rates[0]->getBase());
        self::assertEquals(new Decimal('.81'), $rates[0]->getValue());

        self::assertEquals($rateTimestamp, $rates[1]->getTimestamp());
        self::assertEquals($base, $rates[1]->getBase());
        self::assertEquals(new Decimal('.72'), $rates[1]->getValue());
    }

    private static function getRatesObject(int $timestamp, string $base, Decimal $rate): Response
    {
        return Factory::create([
            'success' => true,
            'timestamp' => $timestamp,
            'base' => $base,
            'date' => '2023-11-30',
            'rates' => [
                'EUR' => (new Decimal('.9'))->mul($rate),
                'GBP' => (new Decimal('.8'))->mul($rate),
            ]
        ]);
    }
}

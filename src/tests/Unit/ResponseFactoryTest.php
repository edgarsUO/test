<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\RatesResponse\CurrencyRate;
use App\DTO\RatesResponse\Factory;
use DateTimeImmutable;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;

final class ResponseFactoryTest extends TestCase
{
    /** @test */
    public function itParsesResponse(): void
    {
        $factory = new Factory();
        $response = $factory->create(self::data());

        self::assertFalse($response->success);
        self::assertEquals(1234567890, $response->timestamp);
        self::assertEquals(DateTimeImmutable::class, get_class($response->timestamp()));
        self::assertEquals('USD', $response->base);
        self::assertEquals('USD', $response->base());
        self::assertEquals('2023-11-30', $response->date);
        self::assertIsArray($response->rates);
        self::assertIsArray($response->rates());

        $rates = $response->rates();
        self::assertCount(2, $rates);
        self::assertEquals(CurrencyRate::class, get_class($rates[0]));
        self::assertEquals('EUR', $rates[0]->currency);
        self::assertEquals(new Decimal('.95'), $rates[0]->value);
        self::assertEquals('GBP', $rates[1]->currency);
        self::assertEquals(new Decimal('.8'), $rates[1]->value);
    }

    private static function data(): array
    {
        return [
            'success' => false,
            'timestamp' => 1234567890,
            'base' => 'USD',
            'date' => '2023-11-30',
            'rates' => [
                'EUR' => .95,
                'GBP' => .8
            ]
        ];
    }
}

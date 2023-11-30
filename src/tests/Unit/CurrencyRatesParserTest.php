<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\RatesResponse\Response;
use App\Parser\CurrencyRatesParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CurrencyRatesParserTest extends TestCase
{
    private CurrencyRatesParser $parser;
    private ValidatorInterface $validator;

    public function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->parser = new CurrencyRatesParser(
            $this->validator
        );

        parent::setUpBeforeClass();
    }

    /** @test */
    public function itThrowsExceptionIfValidationFails(): void
    {
        $violations = ConstraintViolationList::createFromMessage('Value is not valid');

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn($violations);

        $this->expectException(ValidationFailedException::class);
        $this->parser->parse([]);
    }

    /** @test */
    public function itReturnsResponseDTO(): void
    {
        $violations = ConstraintViolationList::createFromMessage('Value is not valid');
        $violations->remove(0);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn($violations);

        $response = $this->parser->parse([]);
        self::assertEquals(Response::class, get_class($response));
    }
}

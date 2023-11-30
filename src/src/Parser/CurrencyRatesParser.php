<?php declare(strict_types=1);

namespace App\Parser;

use App\DTO\RatesResponse\Response as RatesResponse;
use App\DTO\RatesResponse\Factory;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class CurrencyRatesParser
{
    /** @codeCoverageIgnore  */
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function parse(array $response): RatesResponse
    {
        $response = Factory::create($response);
        $violations = $this->validator->validate($response);

        if ($violations->count()) {
            throw new ValidationFailedException(
                'Rates response failed validation.',
                $violations
            );
        }

        return $response;
    }
}

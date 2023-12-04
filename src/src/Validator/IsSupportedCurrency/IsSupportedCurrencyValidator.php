<?php declare(strict_types=1);

namespace App\Validator\IsSupportedCurrency;

use App\Repository\RateRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class IsSupportedCurrencyValidator extends ConstraintValidator
{
    public function __construct(private readonly RateRepository $rates)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsSupportedCurrency) {
            throw new UnexpectedTypeException($constraint, IsSupportedCurrency::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $supportedCurrencies = $this->rates->supportedCurrencies();
        if (!in_array($value, $supportedCurrencies)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}

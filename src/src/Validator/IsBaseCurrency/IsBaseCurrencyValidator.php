<?php declare(strict_types=1);

namespace App\Validator\IsBaseCurrency;

use App\Enum\CurrencyEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class IsBaseCurrencyValidator extends ConstraintValidator
{
    public function __construct(private readonly CurrencyEnum $baseCurrency)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsBaseCurrency) {
            throw new UnexpectedTypeException($constraint, IsBaseCurrency::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ($this->baseCurrency->value === $value) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}

<?php declare(strict_types=1);

namespace App\Exception;

use Decimal\Decimal;
use UnexpectedValueException;

final class TransactionException extends UnexpectedValueException
{
    public static function currencyMismatch(string $currency): self
    {
        return new self(sprintf('Receiver account currency does not match requested: %s', $currency));
    }

    public static function lowBalance(Decimal $deductionAmount): self
    {
        return new self(sprintf('Sender balance is lower than deduction amount %.6f', $deductionAmount));
    }
}

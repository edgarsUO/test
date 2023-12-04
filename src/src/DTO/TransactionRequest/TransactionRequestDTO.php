<?php declare(strict_types=1);

namespace App\DTO\TransactionRequest;

use App\Enum\CurrencyEnum;
use App\Enum\DecimalPrecisionEnum;
use App\Validator\IsSupportedCurrency\IsSupportedCurrency;
use Decimal\Decimal;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class TransactionRequestDTO
{
    #[Assert\NotBlank(message: 'Sender uuid is not defined')]
    #[Assert\Uuid(message: 'Sender uuid is not valid')]
    public ?string $sender;

    #[Assert\NotBlank(message: 'Receiver uuid is not defined.')]
    #[Assert\Uuid(message: 'Receiver uuid is not valid')]
    public ?string $receiver;

    #[Assert\NotBlank(message: 'Currency is not defined')]
    #[Assert\Length(exactly: 3, exactMessage: 'Currency code be 3 characters long')]
    #[Assert\Type(type: 'upper', message: 'Currency code must be uppercase')]
    #[IsSupportedCurrency]
    public ?string $currency;

    #[Assert\NotBlank(message: 'Amount is not defined')]
    #[Assert\Type(type: 'numeric', message: 'Amount must be numeric')]
    #[Assert\Positive(message: 'Amount must be greater than zero')]
    public ?string $amount;

    public function sender(): UuidInterface
    {
        return UUid::fromString($this->sender);
    }

    public function receiver(): UuidInterface
    {
        return UUid::fromString($this->receiver);
    }

    public function currency(): CurrencyEnum
    {
        return CurrencyEnum::from($this->currency);
    }

    public function amount(): Decimal
    {
        return (new Decimal($this->amount, DecimalPrecisionEnum::PRECISION->value))
            ->round(DecimalPrecisionEnum::SCALE->value);
    }
}

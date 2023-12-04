<?php declare(strict_types=1);

namespace App\Exception;

use DomainException;
use Ramsey\Uuid\UuidInterface;

final class AccountException extends DomainException
{
    public static function senderNotFound(UuidInterface $uuid): self
    {
        return new self(sprintf('Sender not found by given uuid: %s', $uuid->toString()));
    }

    public static function receiverNotFound(UuidInterface $uuid): self
    {
        return new self(sprintf('Receiver not found by given uuid: %s', $uuid->toString()));
    }
}

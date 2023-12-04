<?php declare(strict_types=1);

namespace App\DTO\ClientRequest;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class ClientRequestDTO
{
    #[Assert\NotBlank(message: 'Uuid is not defined')]
    #[Assert\Uuid]
    public ?string $uuid;

    public function uuid(): UuidInterface
    {
        return UUid::fromString($this->uuid);
    }
}

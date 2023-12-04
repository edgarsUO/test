<?php declare(strict_types=1);

namespace App\DTO\AccountRequest;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class AccountRequestDTO
{
    #[Assert\NotBlank(message: 'Uuid is not defined')]
    #[Assert\Uuid]
    public ?string $uuid;

    #[Assert\NotBlank(message: 'Limit is not defined.', allowNull: true)]
    #[Assert\PositiveOrZero(message: 'Limit must be positive or zero')]
    #[Assert\Expression(
        expression: 'this.limit === null || this.offset !== null',
        message: 'Offset and limit must be defined'
    )]
    public ?int $limit = null;

    #[Assert\NotBlank(message: 'Offset is not defined.', allowNull: true)]
    #[Assert\PositiveOrZero(message: 'Offset must be positive or zero')]
    #[Assert\Expression(
        expression: 'this.offset === null || this.limit !== null',
        message: 'Offset and limit must be defined'
    )]
    public ?int $offset = null;

    public function uuid(): UuidInterface
    {
        return UUid::fromString($this->uuid);
    }
}

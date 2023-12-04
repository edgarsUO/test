<?php declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

final class UuidNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        return $object->toString();
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof UuidInterface;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): UuidInterface
    {
        try {
            return Uuid::fromString($data);
        } catch (Throwable $exception) {
            throw new UnexpectedValueException(message: $exception->getMessage(), previous: $exception);
        }
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return UuidInterface::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            UuidInterface::class => true,
        ];
    }
}

<?php declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Decimal\Decimal;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

final class DecimalNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        return $object->toString();
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Decimal;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): Decimal
    {
        try {
            return new Decimal($data);
        } catch (Throwable $exception) {
            throw new UnexpectedValueException(message: $exception->getMessage(), previous: $exception);
        }
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return Decimal::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Decimal::class => true,
        ];
    }
}

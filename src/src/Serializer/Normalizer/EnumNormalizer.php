<?php declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;
use UnitEnum;

class EnumNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        return $object->value;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof UnitEnum;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): UnitEnum
    {
        try {
            return $type::from($data);
        } catch (Throwable $exception) {
            throw new UnexpectedValueException(message: $exception->getMessage(), previous: $exception);
        }
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type() instanceof UnitEnum;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            UnitEnum::class => true,
        ];
    }
}

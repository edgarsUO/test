<?php declare(strict_types=1);

namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Decimal\Decimal;

class PhpDecimal extends Type
{
    public const PHP_DECIMAL = 'php_decimal';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDecimalTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Decimal
    {
        return new Decimal($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->toString();
    }

    public function getName(): string
    {
        return self::PHP_DECIMAL;
    }
}


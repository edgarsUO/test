<?php declare(strict_types=1);

namespace App\Validator\IsSupportedCurrency;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsSupportedCurrency extends Constraint
{
    public string $message = 'The currency code "{{ string }}" is not supported, no rate available';
    public string $mode = 'strict';

    // all configurable options must be passed to the constructor
    public function __construct(string $mode = null, string $message = null, array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }
}

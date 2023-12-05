<?php declare(strict_types=1);

namespace App\Validator\IsSupportedCurrency;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsSupportedCurrency extends Constraint
{
    public string $message = 'The currency "{{ string }}" is not supported';
    public string $mode = 'strict';

    public function __construct(string $mode = null, string $message = null, array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }
}

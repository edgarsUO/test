<?php declare(strict_types=1);

namespace App\Validator\IsBaseCurrency;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsBaseCurrency extends Constraint
{
    public string $message = 'The base currency value "{{ string }}" does not match environment configuration';
    public string $mode = 'strict';

    // all configurable options must be passed to the constructor
    public function __construct(string $mode = null, string $message = null, array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }
}

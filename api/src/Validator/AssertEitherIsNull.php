<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

#[\Attribute]
class AssertEitherIsNull extends Constraint {
    public string $messageBothNull;
    public string $messageNoneNull;
    public string $other;

    public function __construct(?array $options = null, ?string $other = null, ?string $messageBothNull = null, ?string $messageNoneNull = null, ?array $groups = null, $payload = null) {
        $this->messageBothNull = $messageBothNull ?? $options['messageBothNull'] ?? 'Either this value or {{ other }} should not be null.';
        $this->messageNoneNull = $messageNoneNull ?? $options['messageNoneNull'] ?? 'Either this value or {{ other }} should be null.';

        $otherValue = $other ?? $options['other'] ?? null;
        if (null === $otherValue) {
            throw new InvalidArgumentException('The "other" option must be the name of another property.');
        }

        $this->other = $otherValue;

        parent::__construct(null, $groups, $payload);
    }
}

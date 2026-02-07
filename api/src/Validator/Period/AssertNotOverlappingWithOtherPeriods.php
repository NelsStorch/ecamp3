<?php

namespace App\Validator\Period;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AssertNotOverlappingWithOtherPeriods extends Constraint {
    public const DEFAULT_MESSAGE = 'Periods must not overlap.';
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? self::DEFAULT_MESSAGE;

        parent::__construct(null, $groups, $payload);
    }
}

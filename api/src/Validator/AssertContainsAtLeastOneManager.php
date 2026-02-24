<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertContainsAtLeastOneManager extends Constraint {
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'must have at least one manager.';

        parent::__construct(null, $groups, $payload);
    }
}

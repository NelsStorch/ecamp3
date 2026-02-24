<?php

namespace App\Validator\ContentNode;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertAttachedToRoot extends Constraint {
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'Must be attached to the root layout.';

        parent::__construct(null, $groups, $payload);
    }
}

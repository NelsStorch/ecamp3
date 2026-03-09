<?php

namespace App\Validator\ContentNode;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertNoRootChange extends Constraint {
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'Must belong to the same root.';

        parent::__construct(null, $groups, $payload);
    }
}

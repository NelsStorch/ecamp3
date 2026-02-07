<?php

namespace App\Validator\ChecklistItem;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertBelongsToSameChecklist extends Constraint {
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'Must belong to the same checklist.';

        parent::__construct(null, $groups, $payload);
    }
}

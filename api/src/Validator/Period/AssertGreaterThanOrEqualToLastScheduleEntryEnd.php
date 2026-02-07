<?php

namespace App\Validator\Period;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertGreaterThanOrEqualToLastScheduleEntryEnd extends Constraint {
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'Due to existing schedule entries, end-date can not be earlier then {{ endDate }}';

        parent::__construct(null, $groups, $payload);
    }
}

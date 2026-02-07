<?php

namespace App\Validator\Period;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertLessThanOrEqualToEarliestScheduleEntryStart extends Constraint {
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'Due to existing schedule entries, start-date can not be later then {{ startDate }}';

        parent::__construct(null, $groups, $payload);
    }
}

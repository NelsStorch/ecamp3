<?php

namespace App\Validator\Period;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertLessThanOrEqualToEarliestScheduleEntryStart extends Constraint {
    public function __construct(
        public readonly string $message = 'Due to existing schedule entries, start-date can not be later then {{ startDate }}',
        ?array $groups = null,
        $payload = null
    ) {
        parent::__construct(null, $groups, $payload);
    }
}

<?php

namespace App\Validator\ColumnLayout;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertNoOrphanChildren extends Constraint {
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'The following slots still have child contents and should be present in the columns: {{ slots }}';

        parent::__construct(null, $groups, $payload);
    }
}

<?php

namespace App\Validator\ColumnLayout;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertColumWidthsSumTo12 extends Constraint {
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'Expected column widths to sum to 12, but got a sum of {{ sum }}';

        parent::__construct(null, $groups, $payload);
    }
}

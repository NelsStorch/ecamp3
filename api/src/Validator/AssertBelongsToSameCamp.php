<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertBelongsToSameCamp extends Constraint {
    public string $message;
    public bool $compareToPrevious;

    /**
     * AssertBelongsToSameCamp constructor.
     *
     * @param bool $compareToPrevious in case the camp getter considers the annotated property, use this option (only when updating)
     */
    public function __construct(?array $options = null, ?bool $compareToPrevious = null, ?string $message = null, ?array $groups = null, mixed $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'Must belong to the same camp.';
        $this->compareToPrevious = $compareToPrevious ?? $options['compareToPrevious'] ?? false;

        parent::__construct(null, $groups, $payload);
    }
}

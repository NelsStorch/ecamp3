<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertLastCollectionItemIsNotDeleted extends Constraint {
    public const IS_EMPTY_ERROR = 'IS_EMPTY_ERROR';
    public string $message;

    public function __construct(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null
    ) {
        $this->message = $message ?? $options['message'] ?? 'Cannot delete the last entry.';

        parent::__construct(null, $groups, $payload);
    }
}

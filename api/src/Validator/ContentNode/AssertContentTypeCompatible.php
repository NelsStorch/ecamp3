<?php

namespace App\Validator\ContentNode;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertContentTypeCompatible extends Constraint {
    public string $message;

    public function __construct(?array $options = null, ?string $message = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? 'Selected contentType {{ contentTypeName }} is incompatible with entity of type {{ givenEntityClass }} (it can only be used with entities of type {{ expectedEntityClass }}).';

        parent::__construct(null, $groups, $payload);
    }
}

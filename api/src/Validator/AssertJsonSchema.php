<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AssertJsonSchema extends Constraint {
    public string $message;

    public array $schema;

    public function __construct(?array $options = null, ?string $message = null, ?array $schema = null, ?array $groups = null, $payload = null) {
        $this->message = $message ?? $options['message'] ?? "Provided JSON doesn't match required schema ({{ schemaError }}).";
        $this->schema = $schema ?? $options['schema'] ?? [
            'type' => 'object',
        ];

        parent::__construct(null, $groups, $payload);
    }
}

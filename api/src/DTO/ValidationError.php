<?php

namespace App\DTO;

use ApiPlatform\ApiResource\Error;
use ApiPlatform\Metadata\ApiProperty;

class ValidationError extends Error {
    public function __construct(
        private readonly ?string $title,
        private readonly int $status,
        private readonly ?string $detail,
        private readonly ?string $instance,
        private readonly array $violations = [],
        private readonly string $type = 'about:blank',
    ) {
        parent::__construct(
            type: $this->type,
            title: $this->title,
            status: $this->status,
            detail: $this->detail,
            instance: $this->instance,
        );
    }

    #[ApiProperty]
    public function getViolations(): array {
        return $this->violations;
    }
}

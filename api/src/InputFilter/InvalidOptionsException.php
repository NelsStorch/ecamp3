<?php

namespace App\InputFilter;

class InvalidOptionsException extends \RuntimeException {
    public function __construct(string $message, private array $options) {
        parent::__construct($message);
    }

    public function getOptions() {
        return $this->options;
    }
}

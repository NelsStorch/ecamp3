<?php

namespace App\Validator\ContentNode;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AssertSlotSupportedByParent extends Constraint {
    public const MESSAGE = 'This value should be one of [{{ supportedSlotNames }}], was {{ value }}.';
    public const NO_PARENT_MESSAGE = 'This value must be null because this content_node has no parent.';
    public const PARENT_DOES_NOT_SUPPORT_CHILDREN = 'The parent of this content_node does not support children.';

    public readonly string $message;
    public readonly string $noParentMessage;
    public readonly string $parentDoesNotSupportChildrenMessage;

    public function __construct(
        ?string $message = null,
        ?string $noParentMessage = null,
        ?string $parentDoesNotSupportChildrenMessage = null,
        ?array $options = null,
        ?array $groups = null,
        $payload = null
    ) {
        $this->message = $message ?? $options['message'] ?? self::MESSAGE;
        $this->noParentMessage = $noParentMessage ?? $options['noParentMessage'] ?? self::NO_PARENT_MESSAGE;
        $this->parentDoesNotSupportChildrenMessage = $parentDoesNotSupportChildrenMessage ?? $options['parentDoesNotSupportChildrenMessage'] ?? self::PARENT_DOES_NOT_SUPPORT_CHILDREN;

        parent::__construct(null, $groups, $payload);
    }
}

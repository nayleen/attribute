<?php

declare(strict_types = 1);

namespace Bakabot\Attribute\Exception;

use LogicException;

final class MissingAttributeException extends LogicException
{
    /**
     * @param class-string $class
     * @param class-string $attribute
     */
    public function __construct(string $class, string $attribute)
    {
        parent::__construct(
            sprintf(
                "Class [%s] doesn't have the [%s] attribute.",
                $class,
                $attribute
            )
        );
    }
}

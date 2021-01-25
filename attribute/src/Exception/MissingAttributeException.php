<?php

declare(strict_types = 1);

namespace Bakabot\Attribute\Exception;

use LogicException;

final class MissingAttributeException extends LogicException
{
    /**
     * @psalm-param class-string $class
     * @psalm-param class-string $attribute
     * @param string $class
     * @param string $attribute
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

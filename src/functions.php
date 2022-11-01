<?php

declare(strict_types = 1);

namespace Nayleen\Attribute;

/**
 * @param class-string|object $class
 * @param class-string $attribute
 * @param callable|scalar $default
 */
function get(string|object $class, string $attribute, mixed $default = null): mixed
{
    // No default provided - strict testing for the property's existence
    if (func_num_args() === 2) {
        return AttributeValueGetter::get($class, $attribute);
    }

    return AttributeValueGetter::get($class, $attribute, $default);
}

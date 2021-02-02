<?php

declare(strict_types = 1);

namespace Bakabot\Attribute;

/**
 * @psalm-param class-string|object $class
 * @psalm-param class-string $attribute
 * @psalm-param callable|scalar $default
 */
function attr(string|object $class, string $attribute, mixed $default = null): mixed
{
    // No default provided - strict testing for the property's existence
    if (func_num_args() === 2) {
        return AttributeValueGetter::getAttributeValue($class, $attribute);
    }

    return AttributeValueGetter::getAttributeValue($class, $attribute, $default);
}

/**
 * @psalm-param class-string|object $class
 * @psalm-param class-string $attribute
 * @psalm-param callable|scalar $default
 */
function getValue(string|object $class, string $attribute, mixed $default = null): mixed
{
    // No default provided - strict testing for the property's existence
    if (func_num_args() === 2) {
        return AttributeValueGetter::getAttributeValue($class, $attribute);
    }

    return AttributeValueGetter::getAttributeValue($class, $attribute, $default);
}

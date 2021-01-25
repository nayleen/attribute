<?php

declare(strict_types = 1);

namespace Bakabot\Attribute;

use Bakabot\Attribute\Exception\MissingAttributeException;
use ReflectionClass;

abstract class AttributeValueGetter
{
    private static array $resolvedAttributeValues = [];

    public static function getAttributeValue(string|object $class, string $attribute, mixed $default = null): mixed
    {
        $class = is_object($class) ? $class::class : $class;

        if (!isset(self::$resolvedAttributeValues[$class][$attribute])) {
            $attributes = (new ReflectionClass($class))->getAttributes($attribute);

            if (count($attributes) === 0) {
                // No default provided - strict testing for the property's existence
                if (func_num_args() === 2) {
                    throw new MissingAttributeException($class, $attribute);
                }

                return self::$resolvedAttributeValues[static::class][$attribute] = is_callable($default)
                    ? $default()
                    : $default;
            }

            self::$resolvedAttributeValues[static::class][$attribute] = array_pop($attributes)->getArguments()[0];
        }

        return self::$resolvedAttributeValues[static::class][$attribute];
    }
}

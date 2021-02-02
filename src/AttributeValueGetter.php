<?php

declare(strict_types = 1);

namespace Bakabot\Attribute;

use Bakabot\Attribute\Exception\MissingAttributeException;
use ReflectionAttribute;
use ReflectionClass;

final class AttributeValueGetter
{
    private static array $resolvedAttributeValues = [];

    public static function getAttributeValue(string|object $class, string $attribute, mixed $default = null): mixed
    {
        $class = is_object($class) ? $class::class : $class;

        if (!isset(self::$resolvedAttributeValues[$class][$attribute])) {
            $attributes = (new ReflectionClass($class))->getAttributes($attribute);
            $attributeCount = count($attributes);

            if ($attributeCount === 0) {
                // No default provided - strict testing for the property's existence
                if (func_num_args() === 2) {
                    throw new MissingAttributeException($class, $attribute);
                }

                return self::$resolvedAttributeValues[$class][$attribute] = is_callable($default)
                    ? $default()
                    : $default;
            }

            $value = $attributeCount > 1 && current($attributes)->isRepeated()
                ? array_map(static fn(ReflectionAttribute $attr) => $attr->getArguments()[0], $attributes)
                : array_pop($attributes)->getArguments()[0];

            self::$resolvedAttributeValues[$class][$attribute] = $value;
        }

        return self::$resolvedAttributeValues[$class][$attribute];
    }
}

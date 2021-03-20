<?php

declare(strict_types = 1);

namespace Bakabot\Attribute;

use Bakabot\Attribute\Exception\MissingAttributeException;
use ReflectionAttribute;
use ReflectionClass;

final class AttributeValueGetter
{
    /** @var array<class-string, array<class-string, mixed>> */
    private static array $resolvedAttributeValues = [];

    /**
     * @param class-string|object $class
     * @param class-string $attribute
     * @param callable|scalar $default
     * @return mixed
     */
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

                $value = is_callable($default)
                    ? $default()
                    : $default;
            } elseif ($attributeCount > 1 && current($attributes)->isRepeated()) {
                $value = array_map([self::class, 'extractValue'], $attributes);
            } else {
                /** @var mixed $value */
                $value = self::extractValue(array_pop($attributes));
            }

            assert(is_scalar($value) || is_array($value));

            self::$resolvedAttributeValues[$class][$attribute] = $value;
        }

        return self::$resolvedAttributeValues[$class][$attribute];
    }

    private static function extractValue(ReflectionAttribute $attribute): mixed
    {
        $arguments = $attribute->getArguments();

        return $arguments[0] ?? null;
    }
}

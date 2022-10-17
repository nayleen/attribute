<?php

declare(strict_types = 1);

namespace Nayleen\Attribute;

use Nayleen\Attribute\Exception\MissingAttributeException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

final class AttributeValueGetter
{
    /** @var array<class-string, array<class-string, mixed>> */
    private static array $resolvedAttributeValues = [];

    /**
     * @param class-string|object $class
     * @param class-string $attribute
     * @param null|callable|scalar|array $default
     * @return mixed
     * @throws MissingAttributeException
     * @throws ReflectionException
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

                if (!is_callable($default)) {
                    return $default;
                }

                $value = $default();
            } else if ($attributeCount > 1 && current($attributes)->isRepeated()) {
                $value = array_map([self::class, 'extractValue'], $attributes);
            } else {
                /** @var mixed $value */
                $value = self::extractValue(array_pop($attributes));
            }

            assert($value === null || is_scalar($value) || is_array($value));

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

<?php

declare(strict_types = 1);

namespace Nayleen\Attribute;

use Nayleen\Attribute\Exception\MissingAttributeException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

final class AttributeValueGetter
{
    /**
     * @var array<class-string, ReflectionAttribute[]>
     */
    private static array $classAttributes = [];

    /**
     * @param class-string|object $class
     * @param class-string $attribute
     * @param null|array|callable|scalar $default
     *
     * @throws MissingAttributeException
     * @throws ReflectionException
     */
    public static function get(string|object $class, string $attribute, mixed $default = null): mixed
    {
        $class = is_object($class) ? $class::class : $class;

        if (!isset(self::$classAttributes[$class])) {
            self::$classAttributes[$class] = (new ReflectionClass($class))->getAttributes($attribute);
        }

        $attributes = self::$classAttributes[$class];
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
        } elseif ($attributeCount > 1 && reset($attributes)->isRepeated()) {
            $value = array_map(self::value(...), $attributes);
        } else {
            /** @var mixed $value */
            $value = self::value(array_pop($attributes));
        }

        assert($value === null || is_scalar($value) || is_array($value));

        return $value;
    }

    private static function value(ReflectionAttribute $attribute): mixed
    {
        $arguments = $attribute->getArguments();

        return $arguments[0] ?? null;
    }
}

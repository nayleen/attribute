<?php

declare(strict_types = 1);

namespace Nayleen\Attribute;

use Nayleen\Attribute\Exception\MissingAttributeException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

/**
 * @template T of object
 */
final class AttributeValueGetter
{
    /**
     * @var array<class-string<T>, ReflectionAttribute<T>[]>
     */
    private static array $classAttributes = [];

    /**
     * @param class-string<T>|T $class
     * @param class-string $attribute
     * @param callable|mixed[]|scalar|null $default
     *
     * @throws MissingAttributeException
     * @throws ReflectionException
     */
    public static function get(string|object $class, string $attribute, mixed $default = null): mixed
    {
        $class = is_object($class) ? $class::class : $class;

        if (!isset(self::$classAttributes[$class])) {
            $classReflection = new ReflectionClass($class);
            $classAttributes = $classReflection->getAttributes($attribute);
            /** @var ReflectionAttribute<T>[] $classAttributes */
            self::$classAttributes[$class] = $classAttributes;
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

    /**
     * @param ReflectionAttribute<T> $attribute
     */
    private static function value(ReflectionAttribute $attribute): mixed
    {
        $arguments = $attribute->getArguments();

        return $arguments[0] ?? null;
    }
}

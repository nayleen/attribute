<?php

declare(strict_types = 1);

namespace Nayleen\Attribute;

use Attribute;
use Closure;
use Nayleen\Attribute\Exception\MissingAttributeException;
use PHPUnit\Framework\TestCase;

class ClassWithoutAnyAttributes
{
}

#[RepeatableAttribute(1)]
#[RepeatableAttribute(2)]
#[RepeatableAttribute(3)]
class ClassWithRepeatableAttribute
{
}

#[RequiredAttribute('required')]
class ClassWithRequiredAttribute
{
}

#[UnrelatedAttribute]
class ClassWithUnrelatedAttribute
{
}

#[Attribute(Attribute::IS_REPEATABLE)]
class RepeatableAttribute
{
    public function __construct(private $num)
    {
    }
}

#[Attribute]
class RequiredAttribute
{
    public function __construct(private $value)
    {
    }
}

#[Attribute]
class UnrelatedAttribute
{
}

/**
 * @internal
 *
 * @coversNothing
 */
class AttributeValueGetterTest extends TestCase
{
    public static function getCallableVariants(): array
    {
        return [
            'static_method' => [
                Closure::fromCallable([AttributeValueGetter::class, 'getAttributeValue']),
            ],

            'getValue_func' => [
                __NAMESPACE__ . '\getValue',
            ],

            'attr_func' => [
                __NAMESPACE__ . '\attr',
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function class_with_attribute_returns_value(callable $callable): void
    {
        $value = $callable(
            ClassWithRequiredAttribute::class,
            RequiredAttribute::class,
        );

        self::assertSame('required', $value);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function class_with_repeatable_attribute_returns_values(callable $callable): void
    {
        $values = $callable(
            ClassWithRepeatableAttribute::class,
            RepeatableAttribute::class,
        );

        self::assertSame([1, 2, 3], $values);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function class_without_attribute_triggers_exception(callable $callable): void
    {
        $this->expectException(MissingAttributeException::class);

        $callable(ClassWithUnrelatedAttribute::class, RequiredAttribute::class);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function class_without_attribute_with_default_callable(callable $callable): void
    {
        $value = $callable(
            ClassWithUnrelatedAttribute::class,
            RequiredAttribute::class,
            fn () => 'default',
        );

        self::assertSame('default', $value);

        $value = $callable(
            ClassWithUnrelatedAttribute::class,
            RequiredAttribute::class,
        );

        self::assertSame('default', $value);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function class_without_attribute_with_default_value(callable $callable): void
    {
        $value = $callable(
            ClassWithUnrelatedAttribute::class,
            RequiredAttribute::class,
            'default',
        );

        self::assertSame('default', $value);

        $value = $callable(
            ClassWithUnrelatedAttribute::class,
            RequiredAttribute::class,
            null,
        );

        self::assertNull($value);

        $value = $callable(
            ClassWithUnrelatedAttribute::class,
            RequiredAttribute::class,
            [],
        );

        self::assertSame([], $value);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function class_without_attributes_triggers_exception(callable $callable): void
    {
        $this->expectException(MissingAttributeException::class);

        $callable(ClassWithoutAnyAttributes::class, RequiredAttribute::class);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function instance_with_attribute_returns_value(callable $callable): void
    {
        $value = $callable(
            new ClassWithRequiredAttribute(),
            RequiredAttribute::class,
        );

        self::assertSame('required', $value);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function instance_with_repeatable_attribute_returns_values(callable $callable): void
    {
        $values = $callable(
            new ClassWithRepeatableAttribute(),
            RepeatableAttribute::class,
        );

        self::assertSame([1, 2, 3], $values);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function instance_without_attribute_triggers_exception(callable $callable): void
    {
        $this->expectException(MissingAttributeException::class);

        $callable(new ClassWithUnrelatedAttribute(), RequiredAttribute::class);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function instance_without_attribute_with_default_callable(callable $callable): void
    {
        $value = $callable(
            new ClassWithUnrelatedAttribute(),
            RequiredAttribute::class,
            fn () => 'default',
        );

        self::assertSame('default', $value);

        $value = $callable(
            new ClassWithUnrelatedAttribute(),
            RequiredAttribute::class,
        );

        self::assertSame('default', $value);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function instance_without_attribute_with_default_value(callable $callable): void
    {
        $value = $callable(
            new ClassWithUnrelatedAttribute(),
            RequiredAttribute::class,
            'default',
        );

        self::assertSame('default', $value);

        $value = $callable(
            new ClassWithUnrelatedAttribute(),
            RequiredAttribute::class,
            null,
        );

        self::assertNull($value);

        $value = $callable(
            new ClassWithUnrelatedAttribute(),
            RequiredAttribute::class,
            [],
        );

        self::assertSame([], $value);
    }

    /**
     * @test
     *
     * @dataProvider getCallableVariants
     */
    public function instance_without_attributes_triggers_exception(callable $callable): void
    {
        $this->expectException(MissingAttributeException::class);

        $callable(new ClassWithoutAnyAttributes(), RequiredAttribute::class);
    }
}

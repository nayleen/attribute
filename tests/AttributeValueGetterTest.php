<?php

declare(strict_types = 1);

namespace Nayleen\Attribute;

use Attribute;
use Nayleen\Attribute\Exception\MissingAttributeException;
use PHPUnit\Framework\TestCase;

final class ClassWithoutAnyAttributes {}

#[RepeatableAttribute(1)]
#[RepeatableAttribute(2)]
#[RepeatableAttribute(3)]
final class ClassWithRepeatableAttribute {}

#[RequiredAttribute('required')]
final class ClassWithRequiredAttribute {}

#[UnrelatedAttribute]
final class ClassWithUnrelatedAttribute {}

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class RepeatableAttribute
{
    public function __construct(public int $num) {}
}

#[Attribute(Attribute::TARGET_CLASS)]
final class RequiredAttribute
{
    public function __construct(public mixed $value) {}
}

#[Attribute(Attribute::TARGET_CLASS)]
final class UnrelatedAttribute {}

/**
 * @internal
 *
 * @backupStaticAttributes
 */
final class AttributeValueGetterTest extends TestCase
{
    /**
     * @return array<string, array{0: callable}>
     */
    public static function getCallableVariants(): array
    {
        return [
            'static_method' => [
                AttributeValueGetter::get(...),
            ],

            'get_func' => [
                __NAMESPACE__ . '\get',
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

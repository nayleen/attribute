<?php

declare(strict_types = 1);

namespace Bakabot\Attribute;

use Attribute;
use Bakabot\Attribute\Exception\MissingAttributeException;
use PHPUnit\Framework\TestCase;

class ClassWithoutAnyAttributes
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

class AttributeValueGetterTest extends TestCase
{
    /** @test */
    public function instance_without_attributes_triggers_exception(): void
    {
        $this->expectException(MissingAttributeException::class);

        AttributeValueGetter::getAttributeValue(new ClassWithoutAnyAttributes(), RequiredAttribute::class);
    }

    /** @test */
    public function class_without_attributes_triggers_exception(): void
    {
        $this->expectException(MissingAttributeException::class);

        AttributeValueGetter::getAttributeValue(ClassWithoutAnyAttributes::class, RequiredAttribute::class);
    }

    /** @test */
    public function instance_without_attribute_triggers_exception(): void
    {
        $this->expectException(MissingAttributeException::class);

        AttributeValueGetter::getAttributeValue(new ClassWithUnrelatedAttribute(), RequiredAttribute::class);
    }

    /** @test */
    public function class_without_attribute_triggers_exception(): void
    {
        $this->expectException(MissingAttributeException::class);

        AttributeValueGetter::getAttributeValue(ClassWithUnrelatedAttribute::class, RequiredAttribute::class);
    }

    /** @test */
    public function instance_without_attribute_with_default_value(): void
    {
        $value = AttributeValueGetter::getAttributeValue(
            new ClassWithUnrelatedAttribute(),
            RequiredAttribute::class,
            'default'
        );

        self::assertSame('default', $value);
    }

    /** @test */
    public function class_without_attribute_with_default_value(): void
    {
        $value = AttributeValueGetter::getAttributeValue(
            ClassWithUnrelatedAttribute::class,
            RequiredAttribute::class,
            'default'
        );

        self::assertSame('default', $value);
    }

    /** @test */
    public function instance_without_attribute_with_default_callable(): void
    {
        $value = AttributeValueGetter::getAttributeValue(
            new ClassWithUnrelatedAttribute(),
            RequiredAttribute::class,
            fn() => 'default'
        );

        self::assertSame('default', $value);
    }

    /** @test */
    public function class_without_attribute_with_default_callable(): void
    {
        $value = AttributeValueGetter::getAttributeValue(
            ClassWithUnrelatedAttribute::class,
            RequiredAttribute::class,
            fn() => 'default'
        );

        self::assertSame('default', $value);
    }

    /** @test */
    public function instance_with_attribute_returns_value(): void
    {
        $value = AttributeValueGetter::getAttributeValue(
            new ClassWithRequiredAttribute(),
            RequiredAttribute::class
        );

        self::assertSame('required', $value);
    }

    /** @test */
    public function class_with_attribute_returns_value(): void
    {
        $value = AttributeValueGetter::getAttributeValue(
            ClassWithRequiredAttribute::class,
            RequiredAttribute::class
        );

        self::assertSame('required', $value);
    }
}

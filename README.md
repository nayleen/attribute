# nayleen-attribute [![Latest Stable Version](https://poser.pugx.org/nayleen/attribute/v)](//packagist.org/packages/nayleen/attribute) [![License](https://poser.pugx.org/nayleen/attribute/license)](//packagist.org/packages/nayleen/attribute)
Provides accessors to single-value [PHP Attributes](https://www.php.net/manual/en/language.attributes.overview.php).

## Installation
`composer require nayleen/attribute`

## Flavors
For ease of use the library provides three identical way of accessing attribute values:

```php
namespace Nayleen\Attribute;

// functions
attr(string|object $class, string $attribute, mixed $default = null): mixed;
getValue(string|object $class, string $attribute, mixed $default = null): mixed;

// public static method
AttributeValueGetter::getAttributeValue(string|object $class, string $attribute, mixed $default = null): mixed;
```

## Usage
Works on both instances and class names:

```php
use function Nayleen\Attribute\getValue;

#[Attribute]
class SomeAttribute
{
    public function __construct(private string $value) {}
}

#[SomeAttribute('foo')]
class MyClass {}

$value = getValue(MyClass::class, 'SomeAttribute'); // "foo"
$value = getValue(new MyClass(), 'SomeAttribute'); // "foo"
```

---

Throws a `MissingAttributeException` if the attribute is not set:

```php
getValue(MyClass::class, 'UnknownAttribute');
// uncaught Nayleen\Attribute\Exception\MissingAttributeException
```

Unless you provide a default value as a third argument:

```php
getValue(MyClass::class, 'UnknownAttribute', 'foo'); // "foo"
getValue(MyClass::class, 'UnknownAttribute', 'bar'); // "bar"
getValue(MyClass::class, 'UnknownAttribute', 'baz'); // "baz"
```

For heavy lifting or lazy evaluation, a default value can be any `callable`, in which case its resulting value will be cached.

```php
getValue(MyClass::class, 'UnknownAttribute', fn () => 'bar'); // "bar"
getValue(MyClass::class, 'UnknownAttribute'); // still "bar"
```

---

If the attribute is repeatable, it'll return an array of that attribute's values:

```php
use function Nayleen\Attribute\getValue;

#[Attribute(Attribute::IS_REPEATABLE)]
final class RepeatableAttribute
{
    public function __construct(private string $value) {}
}

#[RepeatableAttribute('foo')]
#[RepeatableAttribute('bar')]
class MyClass {}

$value = getValue(MyClass::class, 'RepeatableAttribute'); // ["foo", "bar"]
```

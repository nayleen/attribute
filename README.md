# nayleen-attribute [![Latest Stable Version](https://poser.pugx.org/nayleen/attribute/v)](//packagist.org/packages/nayleen/attribute) [![License](https://poser.pugx.org/nayleen/attribute/license)](//packagist.org/packages/nayleen/attribute)
Provides accessors to single-value [PHP Attributes](https://www.php.net/manual/en/language.attributes.overview.php).

## Installation
`composer require nayleen/attribute`

## Flavors
For ease of use the library provides two identical ways of accessing attribute values:

```php
namespace Nayleen\Attribute;

// function
get(string|object $class, string $attribute, mixed $default = null): mixed;

// public static method
AttributeValueGetter::get(string|object $class, string $attribute, mixed $default = null): mixed;
```

## Usage
Works on both instances and class names:

```php
use function Nayleen\Attribute\get;

#[Attribute]
class SomeAttribute
{
    public function __construct(private string $value) {}
}

#[SomeAttribute('foo')]
class MyClass {}

$value = get(MyClass::class, 'SomeAttribute'); // "foo"
$value = get(new MyClass(), 'SomeAttribute'); // "foo"
```

---

Throws a `MissingAttributeException` if the attribute is not set:

```php
get(MyClass::class, 'UnknownAttribute');
// uncaught Nayleen\Attribute\Exception\MissingAttributeException
```

Unless you provide a default value as a third argument:

```php
get(MyClass::class, 'UnknownAttribute', 'foo'); // "foo"
get(MyClass::class, 'UnknownAttribute', 'bar'); // "bar"
get(MyClass::class, 'UnknownAttribute', 'baz'); // "baz"
```

For heavy lifting or lazy evaluation, a default value can be a `callable`:

```php
get(MyClass::class, 'UnknownAttribute', fn () => 'bar'); // "bar"
```

---

If the attribute is repeatable, it'll return an array of that attribute's values:

```php
use function Nayleen\Attribute\get;

#[Attribute(Attribute::IS_REPEATABLE)]
final class RepeatableAttribute
{
    public function __construct(private string $value) {}
}

#[RepeatableAttribute('foo')]
#[RepeatableAttribute('bar')]
class MyClass {}

$value = get(MyClass::class, 'RepeatableAttribute'); // ["foo", "bar"]
```

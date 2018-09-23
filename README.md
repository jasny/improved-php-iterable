Jasny Iterator Projection
===

[![Build Status](https://travis-ci.org/jasny/iterator-projection.svg?branch=master)](https://travis-ci.org/jasny/iterator-projection)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/iterator-projection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/iterator-projection/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/iterator-projection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/iterator-projection/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/iterator-projection.svg)](https://packagist.org/packages/jasny/iterator-projection)
[![Packagist License](https://img.shields.io/packagist/l/jasny/iterator-projection.svg)](https://packagist.org/packages/jasny/iterator-projection)

Projection operations for [iterators](http://php.net/manual/en/class.iterator.php) (PHP).

Installation
---

    composer require jasny/iterator-projection

Operations
---

**Callback**

* [MapOperation](#mapoperation)
* [MapKeyOperation](#mapkeyoperation)
* [ApplyOperation](#applyoperation)

**Filter**
* [UniqueOperation](#uniqueoperation)

**Sorting**

* [SortOperation](#sortoperation)
* [SortKeyOperation](#sortkeyoperation)

**Projection**

* [GroupOperation](#groupoperation)
* [FlattenOperation](#flattenoperation)
* [ProjectionOperation](#projectionoperation)

**Key/value**

* [ValueOperation](#valueoperation)
* [KeyOperation](#keyoperation)
* [CombineOperation](#combineoperation)
* [FlipOperation](#flipoperation)

**Validation**

* [ExpectTypeOperation](#expecttypeoperation)

Callback
---

### MapOperation

Map all elements of an array or iterator. The keys remain unchanged.

```php
$persons = [
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
];

$iterator = new MapOperation($persons, function(Person $value, string $key): string {
    return sprintf("%s = %s", $key, $value->name);
});
```

### MapKeyOperation

Map all keys elements of an Iterator. The values remain unchanged.

```php
$persons = [
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
];

$iterator = new MapKeyOperation($persons, function(string $key, Person $value): string {
    return sprintf("%s (%s)", $value->name, $key);
});
```

_Caveat: The callback function switches value and key arguments, so the first argument is the key._

### ApplyOperation

Apply a callback to each element. This is a pass-through iterator, any value returned by the callback is ignored.

```php
$persons = [
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
];

$iterator = new ApplyOperation($persons, function(Person $value, string $key): void {
    $value->role = $key;
});
```

Filter
---

### UniqueOperation

Filter to get only unique items. The keys are preserved, skipping duplicate values.

```php
$values = ['foo', 'bar', 'qux', 'foo', 'zoo'];

$iterator = new UniqueOperation($values);
```

You can pass a callback, which should return a value. Filtering on distinct values will be based on that value.

```php
$persons = [
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
];

$iterator = new UniqueOperation($persons, function(Person $value): int {
    return $value->age;
});
```

All values are stored for reference. The callback function can also be used to serialize and hash the value.

```php
$persons = [
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
];

$iterator = new UniqueOperation($persons, function(Person $value): string {
    return hash('sha256', serialize($value));
});
```

The keys of an iterator don't have to be unique. This is unlike an associated array. You may return the key in the
callback to get distinct keys.

```php
$iterator = new UniqueOperation($someGenerator, function($value, $key) {
    return $key;
});
```

Sorting
---

### SortOperation

Sort all elements of an iterator.

```php
$values = ["Charlie", "Echo", "Bravo", "Delta", "Foxtrot", "Alpha"];

$iterator = new SortOperation($values);
```

Instead of using the default sorting, a callback may be passed as user defined comparison function.

```php
$values = ["Charlie", "Echo", "Bravo", "Delta", "Foxtrot", "Alpha"];

$iterator = new SortOperation($values, function($a, $b): int {
    return strlen($a) <=> strlen($b);
});
```

The keys are preserved.

_This is an `IteratorAggregate`. It may require traversing through all elements an putting them in an `ArrayOperation`
for sorting._

### SortKeyOperation

Sort all elements of an iterator based on the key.

```php
$values = [
    "Charlie" => "three",
    "Bravo" => "two",
    "Delta" => "four",
    "Alpha" => "one"
];

$iterator = new SortKeyOperation($values);
```

Similar to `SortOperation`, a callback may be passed as user defined comparison function. 

_This is an `IteratorAggregate`. It may require traversing through all elements an putting them in an `ArrayOperation`
for sorting._

### ReverseOperation

Reverse order of elements of an iterator.

```php
$values = ["Charlie", "Echo", "Bravo", "Delta", "Foxtrot", "Alpha"];

$iterator = new ReverseOperation($values);
```

The keys are preserved.

_This is an `IteratorAggregate`. It may require traversing through all elements an putting them in an `ArrayOperation`
for sorting._

Projection
---

### GroupOperation

Group elements of an iterator.

```php
$objects = [
    (object)['type' => 'one'],
    (object)['type' => 'two'],
    (object)['type' => 'one'],
    (object)['type' => 'three'],
    (object)['type' => 'one'],
    (object)['type' => 'two']
];

$iterator = new GroupOperation($objects, function(\stdClass $object): string {
    return $object->type;
});
```

Alternatively, it's possible to group based on the key.

```php
$values = [
    'alpha' => 'one',
    'bat' => 'two',
    'apple' => 'three',
    'cat' => 'four',
    'air' => 'five',
    'beast' => 'six'
];

$iterator = new GroupOperation($values, function(string $value, string $key): string {
    return substr($key, 0, 1);
});
```

_This is an `IteratorAggregate`. It requires traversing through all elements an putting them in a `CombineOperation` for
grouping._

### FlattenOperation

Walk through all sub-iterables and concatenate them.

```php
$values = [
    ['one', 'two'],
    ['three', 'four', 'five'],
    [],
    ['six']
];

$iterator = new FlattenOperation($values);
```

The entries may be an array, Iterator or IteratorAggregate. Other entries will not be flattened.

```php
$values = [
    ['one', 'two']),
    new \ArrayObject(['three', 'four', 'five']),
    new \EmptyOperation(),
    ['six'],
    'seven'
];

$iterator = new FlattenOperation($values);
```

By default the keys are dropped, replaces by an incrementing counter (so as an numeric array). By passing `true` as
second parameters, the keys are remained.

```php
$values = [
    ['one' => 'uno', 'two' => 'dos'],
    ['three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
    [],
    ['six' => 'seis']
];

$iterator = new FlattenOperation($values, true);
```

### ProjectionOperation
Project each element of an iterator to an associated (or numeric) array. Each element should be an array or object.

For the projection, a mapping `[new key => old key]` must be supplied.

```php
$values = [
    ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
    ['one' => 'yi', 'two' => 'er', 'three' => 'san', 'four' => 'si', 'five' => 'wu'],
    ['one' => 'één', 'two' => 'twee', 'three' => 'drie', 'four' => 'vier', 'five' => 'vijf']
];

$mapping = ['I' => 'one', 'II' => 'two', 'II' => 'three', 'IV' => 'four'];

$iterator = new ProjectionOperation($values, $mapping);
```

If an element doesn't have a specified key, the value will be `null`.

The order of keys of the projected array is always the same as the order of the mapping.

The mapping may also be a numeric array.

```php
$values = [
    ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
    (object)['three' => 'san', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
    new \ArrayObject(['two' => 'twee', 'four' => 'vier'])
];

$mapping = ['one', 'three, 'four'];

$iterator = new ProjectionOperation($values, $mapping);
```

Scalar elements and `DateTime` object are ignored.

Key/value
---

### ValueOperation

Keep the values, drop the keys. The keys become an incremental number. This is comparable to
[`array_values`](https://php.net/array_values).

```php
$values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro'];

$spanish = new ValueOperation($values);
```

### KeyOperation

Use the keys as values. The keys become an incremental number. This is comparable to
[`array_keys`](https://php.net/array_keys).

```php
$values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro'];

$english = new KeyOperation($values);
```

### CombineOperation

Iterator through keys and values.

```php
$english = ['one', 'two', 'three', 'four'];
$spanish = ['uno', 'dos', 'tres', 'cuatro'];

$iterator = new CombineOperation($english, $spanish);
```

The key may be any type and doesn't need to be unique.

```php
$keys = [null, new \stdClass(), 'foo', ['hello', 'world'], 5.2, 'foo'];
$values = ['one', 'two', 'three', 'four', 'five', 'six'];

$iterator = new CombineOperation($keys, $values);
```

The number of elements yielded from the iterator only depends on the number of keys. If there are more keys than
values, the value defaults to `null`. If there are more values than keys, the additional values are not returned.

### FlipOperation

Use values as keys and visa versa.

```php
$values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro'];

$iterator = new FlipOperation($values);
```

Both the value and key may be any type and don't need to be unique.

Validation
---

### ExpectTypeOperation

Assert the type of each element of the array using [`expect_type`](https://github.com/jasny/php-functions#expect_type)
from jasny/php-functions.

As type you can specify any internal type, including `callable` and `scalar`, or a class name.

Multiple types can be specified. The value needs to be one of these types. 

```php
$values = ['one' => 'uno', 'two' => 2, 'three' => new \stdClass(), 'four' => 'cautro'];

$iterator = new AssertTypeOperation($values, ['string', 'int'], \UnexpectedValueException::class);
```

A `Throwable` class name may be specified as third argument. The iterator throws an `UnexpectedValueException` by
default.

A message may be specified as fourth argument, where `%s` will be replaced by the element type.

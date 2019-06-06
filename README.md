![improved PHP library](https://user-images.githubusercontent.com/100821/46372249-e5eb7500-c68a-11e8-801a-2ee57da3e5e3.png)

# iterable

[![Build Status](https://travis-ci.org/improved-php-library/iterable.svg?branch=master)](https://travis-ci.org/improved-php-library/iterable)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/improved-php-library/iterable/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/improved-php-library/iterable/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/improved-php-library/iterable/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/improved-php-library/iterable/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/improved/iterable.svg)](https://packagist.org/packages/improved/iterable)
[![Packagist License](https://img.shields.io/packagist/l/improved/iterable.svg)](https://packagist.org/packages/improved/iterable)

Functional-style operations, such as map-reduce transformations on arrays, [iterators](https://php.net/iterator) and
other [traversable](https://php.net/traversable) objects.

These functions are different from their `array_*` counterparts, as they work with any kind of iterable rather than just
arrays. If you're not familiar with Iterators and Generators in PHP, please first read paragraph
"[What are iterators?](#what-are-iterators)".

The library supports the procedural and object-oriented programming paradigm.

## Installation

    composer require improved/iterable

## Methods

#### Chainable methods

**Mapping**
* [`map(callable $callback)`](#map)
* [`mapKeys(callable $callback)`](#mapkeys)
* [`apply(callable $callback)`](#apply)
* [`then(callable $callback, mixed ...$args)`](#then)
* [`chunk(int $size)`](#chunk)
* [`group(callable $callback)`](#group)
* [`unwind(int|string $column[, int|string|null $mapKey[, bool $preserveKeys]])`](#unwind)
* [`flatten()`](#flatten)
* [`fill(mixed $value)`](#fill)
* [`column(int|string|null $valueColumn[, int|string|null $keyColumn])`](#column)
* [`project(array $mapping)`](#project)
* [`reshape(array $columns)`](#reshape)
* [`values()`](#values)
* [`keys()`](#keys)
* [`setKeys(Traversable $keys)`](#setkeys)
* [`flip()`](#flip)

**Filtering**
* [`filter(callable $matcher)`](#filter)
* [`cleanup()`](#cleanup)
* [`unique([callable $callback])`](#unique)
* [`uniqueKeys()`](#uniquekeys)
* [`limit(int $size)`](#limit)
* [`slice(int $offset[, int $size])`](#slice)
* [`before(callable $matcher[, bool $include])`](#before)
* [`after(callable $matcher[, bool $include])`](#after)

**Sorting**
* [`sort([int|callable $compare[, bool $preserveKeys]])`](#sort)
* [`sortKeys([int|callable $compare])`](#sortkeys)
* [`reverse()`](#reverse)

**Type handling**
* [`typeCheck(string|string[] $type[, \Throwable $error])`](#typecheck)
* [`typeCast(array $type[, \Throwable $error])`](#typecast)

#### Other methods

**General**
* [`getIterator()`](#getiterator)
* [`toArray()`](#toarray)
* [`walk()`](#walk)

**Finding**
* [`first([bool $required])`](#first)
* [`last([bool $required])`](#last)
* [`find(callable $matcher)`](#find)
* [`findKey(callable $matcher)`](#findkey)
* [`min([callable $compare])`](#min)
* [`max([callable $compare])`](#max)

**Aggregation**
* [`count(): int`](#count)
* [`reduce(callable $callback[, mixed $initial])`](#reduce)
* [`sum(): int|float`](#sum)
* [`average(): int|float`](#average)
* [`concat([string $glue]): string`](#concat)

#### Builder methods

* [`stub(string $name)`](#stub)
* [`unstub(string $name, callable callable, mixed ...$args)`](#stub)

## Example

All functions and objects are in the `Improved` namespace. Either alias the namespace as `i` or import each function
individually.

```php
use Improved as i;

$filteredValues = i\iterable_filter($values, function($value) {
   return is_int($value) && $value > 10;
});

$uniqueValues = i\iterable_unique($filteredValues);

$mappedValues = i\iterable_map($uniqueValues, function($value) {
    return $value * $value - 1;
});

$firstValues = i\iterable_slice($mappedValues, 0, 10);

$result = i\iterable_to_array($firstValues);
```

Alternatively use the iterator pipeline.

```php
use Improved\IteratorPipeline\Pipeline;

$result = Pipeline::with($values)
    ->filter(function($value) {
        return is_int($value) && $value < 10;
    })
    ->unique()
    ->map(function($value) {
        return $value * $value - 1;
    })
    ->limit(10)
    ->toArray();
```

## Usage

This library provides Utility methods for creating streams.

`Pipeline` takes an array or `Traversable` object as source argument. The static `with()` method can be used instead of
`new`.

```php
use Improved\IteratorPipeline\Pipeline;

Pipeline::with([
    new Person("Max", 18),
    new Person("Peter", 23),
    new Person("Pamela", 23)
]);

$dirs = new Pipeline(new \DirectoryIterator('some/path'));
```

A pipeline uses [PHP generators](http://php.net/manual/en/language.generators.overview.php), which are forward-only and
non-rewindable. This means a pipeline can only be used one.

### PipelineBuilder

The `PipelineBuilder` can be used to create a blueprint for pipelines. The builder contains the mapping methods of
`Pipeline` and not the other methods.

The static `Pipeline::build()` method can be used as syntax sugar to create a builder.

```php
use Improved\IteratorPipeline\Pipeline;

$blueprint = Pipeline::build()
    ->checkType('string')
    ->filter(function(string $value): bool) {
        strlen($value) > 10;
    });
    
// later
$pipeline = $blueprint->with($iterable);
```

A `PipelineBuilder` is an immutable object, each method call creates a new copy of the builder.

Alternatively the pipeline builder can be invoked, which creates a pipeline and calls `toArray()` on it.

```php
use Improved\IteratorPipeline\Pipeline;

$unique = Pipeline::build()
    ->unique()
    ->values();

$result = $unique($values);
```

The `then()` method can be used to combine two pipeline builder.

```php
use Improved\IteratorPipeline\Pipeline;

$first = Pipeline::build()->unique()->values();
$second = Pipeline::build()->map(function($value) {
    return ucwords($value);
});

$titles = $first->then($second);

$result = $titles($values);
```

### Custom Pipeline class

A `Pipeline` is not an immutable object, unlike the `PipelineBuilder`. Only the `iterable` returned from latest step is
relevant and kept by the pipeline. As such, you can extend the `Pipeline` class and use that any chainable method,
without the object changing.

However is a step returns a `Pipeline` object (including any object that extends the pipeline), the `then` method will
return that object instead of `$this`. This can be used to inject a custom class later in a pipe or in a pipeline
builder.

```php
use Improved\IteratorPipeline\Pipeline;

class MyPipeline extends Pipeline
{
    function product()
    {
        $product = 1;
        
        foreach ($this->iterable as $value) {
            $product *= $value;
        }
        
        return $product;
    }
}
```

##### Starting with your custom class

```php
$product = (new MyPipeline)->column('amount')->product();
```

##### In an existing pipeline

```php
$pipeline = (new Pipeline)->column('amount');

$product = $pipeline 
    ->then(function(iterable $iterable) {
        return new MyPipeline($iterable);
    })
    ->product();
``` 

##### In a pipeline builder

```php
$builder = (new PipelineBuilder)
    ->then(function(iterable $iterable) {
        return new MyPipeline($iterable);
    })
    ->column('amount')
    ->product();
```

This is the only way to get a `PipelineBuilder` to return a custom `Pipeline` class without also creating a custom
`PipelineBuilder`. 


## Method reference

### getIterator

The Pipeline implements the [`IteratorAggregate`](https://php.net/iteratoraggregate) interface. This means it's
traversable. Alternatively you can use `getIterator()`.

### toArray

Copy the elements of the iterator into an array.

```php
Pipeline::with(["one", "two", "three"])
    ->toArray();
```

### walk

Traverse over the iterator, not capturing the values. This is particularly useful after `apply()`.

```php
Pipeline::with($objects)
    ->apply(function($object, $key) {
        $object->type = $key;
    })
    ->walk();
```

## Mapping

### map

Map each element to a value using a callback function.

```php
Pipeline::with([3, 2, 2, 3, 7, 3, 6, 5])
    ->map(function(int $i): int {
        return $i * $i;
    })
    ->toArray(); // [9, 4, 4, 9, 49, 9, 36, 25]
```

The second argument of the callback is the key.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->map(function(string $value, string $key): string {
        return "{$value} {$key}";
    })
    ->toArray(); // ['apple' => 'green apple', 'berry' => 'blue berry', 'cherry' => 'red cherry']
```

### mapKeys

Map the key of each element to a new key using a callback function.

The second argument of the callback is the value and second is the key.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->mapKeys(function(string $value, string $key): string {
        return subst($key, 0, 1);
    })
    ->toArray(); // ['a' => 'green', 'b' => 'blue', 'c' => 'red']
```

### apply

Apply a callback to each element of an iterator. Any value returned by the callback is ignored.


```php
$persons = [
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
];

Pipeline::with($persons)
    ->apply(function(Person $value, string $key): void {
        $value->role = $key;
    })
    ->toArray();
```

### then

The `then()` method can be used for callback that returns a `Generator` or other `Traversable`.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->then(function(\Traversable $values): \Generator {
        foreach ($values as $key => $value) {
            yield $key[0] => "$value $key";
        }
    })
    ->toArray(); // ['a' => 'green apple', 'b' => 'blue berry', 'c' => 'red cherry']
```

It may be used to apply a custom (outer) iterator.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->then(function(\Traversable $values): \Iterator {
        return new MyCustomIterator($values);
    });
```

### chunk

Divide iterable into chunks of specified size.

```php
Pipeline::with(['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'])
    ->chunk(4);
    
// <iterator>[
//     <iterator>['I', 'II', 'III', 'IV'],
//     <iterator>['V', 'VI', 'VII', 'VIII'],
//     <iterator>['IX', 'X']
// ]
```

Chunks are iterators rather than arrays. Keys are preserved.

### group

Group elements of an iterator, with the group name as key and an array of elements as value.

```php
Pipeline::with(['apple', 'berry', 'cherry', 'apricot'])
    ->group(function(string $value): string {
        return $value[0];
    })
    ->toArray();
    
// ['a' => ['apple', 'apricot'], 'b' => ['berry'], 'c' => ['cherry']]
```

The second argument is the key.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red', 'apricot' => 'orange'])
    ->group(function(string $value, string $key): string {
        return $key[0];
    })
    ->toArray();

// ['a' => ['apple' => 'green', 'apricot' => 'orange'], 'b' => ['berry' => 'blue'], 'c' => ['cherry' => 'red']]
```

### flatten

Walk through all sub-iterables and concatenate them.

```php
$groups = [
    ['one', 'two'],
    ['three', 'four', 'five'],
    [],
    ['six'],
    'seven'
];

Pipeline::with($groups)
    ->flatten()
    ->toArray(); // ['one', 'two', 'three', 'four', 'five', 'six', 'seven']
```
By default the keys are dropped, replaces by an incrementing counter (so as an numeric array). By passing `true` as
second parameters, the keys are remained.

### unwind

Deconstruct an iterable property/item for each element. The result is one element for each item in the iterable
property. You must specify which column to unwind.

```php
$elements = [
    ['ref' => 'a', 'numbers' => ['I' => 'one', 'II' => 'two']],
    ['ref' => 'b', 'numbers' => 'three'],
    ['ref' => 'c', 'numbers' => []]
];

Pipeline::with($elements)
    ->unwind('numbers')
    ->toArray();
    
// [
//     ['ref' => 'a', 'numbers' => 'one'],
//     ['ref' => 'a', 'numbers' => 'two'],
//     ['ref' => 'b', 'numbers' => 'three'],
//     ['ref' => 'c', 'numbers' => null]
// ]
```

The second argument is optional, taking a column name to add each key to the element. 

```php
Pipeline::with($elements)
    ->unwind('numbers', 'nrkey')
    ->toArray();
    
// [
//     ['ref' => 'a', 'numbers' => 'one', 'nrkey' => 'I'],
//     ['ref' => 'a', 'numbers' => 'two', 'nrkey' => 'II],
//     ['ref' => 'b', 'numbers' => 'three', 'nrkey' => null],
//     ['ref' => 'c', 'numbers' => null, 'nrkey' => null]
// ]
```

By default each new element of the resulting iterator is a numeric sequence. To preverse the keys, pass `true` as third
argument. Beware that this will result in duplicate keys.

### fill

Set all values of the iterable. Don't touch the keys.

This can be used in combination with `flip` to something similar to `array_fill_keys`.

```php
$fields = ['foo', 'bar', 'qux'];

Pipeline::with($fields)
    ->flip()
    ->fill(42)
    ->toArray(); // ['foo' => 42, 'bar' => 42, 'qux' => 42]
```

### column

Return the values from a single column / property. Each element should be an array or object.

```php
$rows = [
    ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
    ['one' => 'yi', 'two' => 'er', 'three' => 'san', 'four' => 'si', 'five' => 'wu'],
    ['one' => 'één', 'two' => 'twee', 'three' => 'drie', 'five' => 'vijf']
];

Pipeline::with($rows)
    ->column('three')
    ->toArray(); // ['tres', 'san', 'drie']

```

Create key/value pairs by specifying the key.

```php
$rows = [
    ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
    ['one' => 'yi', 'two' => 'er', 'three' => 'san', 'four' => 'si', 'five' => 'wu'],
    ['one' => 'één', 'two' => 'twee', 'three' => 'drie', 'five' => 'vijf']
];

Pipeline::with($rows)
    ->column('three', 'two')
    ->toArray(); // ['dos' => 'tres', 'er' => 'san', 'twee' -=> 'drie']

```

Alternatively you may only specify the key column, using `null` for the value column, to keep the value unmodified.

If an element doesn't have a specified key, the key and/or value will be `null`.


### project

Project each element of an iterator to an associated (or numeric) array. Each element should be an array or object.

For the projection, a mapping `[new key => old key]` must be supplied.

```php
$rows = [
    ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
    ['one' => 'yi', 'two' => 'er', 'three' => 'san', 'four' => 'si', 'five' => 'wu'],
    ['one' => 'één', 'two' => 'twee', 'three' => 'drie', 'five' => 'vijf']
];

Pipeline::with($rows)
    ->project(['I' => 'one', 'II' => 'two', 'II' => 'three', 'IV' => 'four'])
    ->toArray();

// [
//   ['I' => 'uno', 'II' => 'dos', 'III' => 'tres', 'IV' => 'cuatro'],
//   ['I' => 'yi', 'II' => 'er', 'III' => 'san', 'IV' => 'si'],
//   ['I' => 'één', 'II' => 'twee', 'III' => 'drie', 'IV' => null]
// ]
```

If an element doesn't have a specified key, the value will be `null`.

The order of keys of the projected array is always the same as the order of the mapping. The mapping may also be a
numeric array.

### reshape

Reshape each element of an iterator, adding or removing properties or keys.

The method takes an array with the column name as key. The value may be a boolean, specifying if th column should
remain or be removed. Alternatively the column may be a string or int, renaming the column name (key).

Columns that are not specified are untouched. This has the same effect as `'column' => true`.

```php
$rows = [
    ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
    ['three' => 'san', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
    ['two' => 'twee', 'four' => 'vier']
];

Pipeline::with($rows)
    ->reshape(['one' => true, 'two' => false, 'three' => 'III', 'four' => 0])
    ->toArray();

// [
//     ['one' => 'uno', 'five' => 'cinco', 'III' => 'tres', 0 => 'cuatro'],
//     ['five' => 'wu', 'III' => 'san', 0 => 'si'],
//     [0 => 'vier']
// ];
```

Note that unlike with `project()`, the array or object is modified. If the element does not have the specific key, it's
ignored. If the element is not an object or array, it's untouched.

### values

Keep the values, drop the keys. The keys become an incremental number. This is comparable to
[`array_values`](https://php.net/array_values).

```php
Pipeline::with(['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four'])
    ->values()
    ->toArray(); // ['one', 'two', 'three', 'four']
```

### keys

Use the keys as values. The keys become an incremental number. This is comparable to
[`array_keys`](https://php.net/array_keys).

```php
Pipeline::with(['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four'])
    ->keys()
    ->toArray(); // ['I', 'II', 'III', 'IV']
```

### setKeys

Use another iterator as keys and the current iterator as values.

```php
Pipeline::with(['one', 'two', 'three', 'four'])
    ->setKeys(['I', 'II', 'III', 'IV'])
    ->toArray(); // ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four']
```

The key may be any type and doesn't need to be unique.

The number of elements yielded from the iterator only depends on the number of keys. If there are more keys than
values, the value defaults to `null`. If there are more values than keys, the additional values are not returned.

### flip

Use values as keys and visa versa.

```php
Pipeline::with(['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro'])
    ->flip()
    ->toArray(); // ['uno' => 'one', 'dos' => 'two', 'tres' => 'three', 'cuatro' => 'four']
```

Both the value and key may be any type and don't need to be unique.


## Filtering

### filter

Eliminate elements based on a criteria.

The callback function is required and should return a boolean.

```php
Pipeline::with([3, 2, 2, 3, 7, 3, 6, 5])
    ->filter(function(int $i): bool {
        return $i % 2 === 0; // is even
    })
    ->toArray(); // [1 => 2, 2 => 2, 6 => 6]
```

The second argument of the callback is the key.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red', 'apricot' => 'orange'])
    ->filter(function(string $value, string $key): bool {
        return $key[0] === 'a';
    })
    ->toArray(); // ['apple' => 'green', 'apricot' => 'orange']
```

### cleanup()

Filter out `null` values or `null` keys from iterable.

```php
Pipeline::with(['one', 'two', null, 'four', null])
    ->cleanup()
    ->toArray();

// [0 => 'one', 1 => 'two', 3 => 'four']
```

With iterators, keys may be of any type. Elements with `null` keys are also filtered out.

### unique

Filter on unique elements.

```php
Pipeline::with(['foo', 'bar', 'qux', 'foo', 'zoo'])
    ->unique()
    ->toArray(); // [0 => 'foo', 1 => 'bar', 2 => qux, 4 => 'zoo']
```

You can pass a callback, which should return a value. Filtering on distinct values will be based on that value.

```php
$persons = [
    new Person("Max", 18),
    new Person("Peter", 23),
    new Person("Pamela", 23)
];

Pipeline::with($persons)
    ->unique(function(Person $value): int {
        return $value->age;
    })
    ->toArray();

// [0 => Person {'name' => "Max", 'age' => 18}, 1 => Person {'name' => "Peter", 'age' => 23}]
```

All values are stored for reference. The callback function can also be used to serialize and hash the value.

```php
Pipeline::with($persons)
    ->unique(function(Person $value): int {
        return hash('sha256', serialize($value));
    });
});
```

The seconds argument is the key.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red', 'apricot' => 'orange'])
    ->unique(function(string $value, string $key): string {
        return $key[0];
    })
    ->toArray(); // ['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red']
```

Uses strict comparison (`===`), so '10' and 10 won't match.

### uniqueKeys

The keys of an iterator don't have to be unique (and don't have to be a scalar). This is unlike an associated array.

The `uniqueKeys()` method filters our duplicate keys.

```php
$someGenerator = function($max) {
    for ($i = 0; $i < $max; $i++) {
        $key = substr(md5((string)$i), 0, 1); // char [0-9a-f]
        yield $key => $i;
    }
};

Pipeline::with($someGenerator(1000))
    ->uniqueKeys()
    ->toArray();

// ['c' => 0, 'e' => 3, 'a' => 4, 1 => 6, 8 => 7, 4 => 9, 'd' => 10, 6 => 11 9 => 15 7 => 17,
//     3 => 21, 'b' => 22, 0 => 27, 'f' => 44, 2 => 51, 5 => 91]
```

### limit

Get only the first elements of an iterator.

```php
Pipeline::with([3, 2, 2, 3, 7, 3, 6, 5])
    ->limit(3)
    ->toArray(); // [3, 2, 2]
```

### slice

Get a limited subset of the elements using an offset.

```php
Pipeline::with([3, 2, 2, 3, 7, 3, 6, 5])
    ->slice(3)
    ->toArray(); // [3, 7, 3, 6, 5]
```

You may also specify a limit.

```php
Pipeline::with([3, 2, 2, 3, 7, 3, 6, 5])
    ->slice(3, 2)
    ->toArray(); // [3, 7]
```

### before

Get elements until a match is found.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'red', 'cherry' => 'red', 'apricot' => 'orange'])
    ->before(function($value, $key) {
        return $value === 'red';
    })
    ->toArray(); // ['apple' => 'green']
```

The seconds argument is the key.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'red', 'cherry' => 'red', 'apricot' => 'orange'])
    ->before(function($value, $key) {
        return $key === 'berry';
    })
    ->toArray(); // ['apple' => 'green']
```

Optionally the matched value can be included in the result

```php
Pipeline::with(['apple' => 'green', 'berry' => 'red', 'cherry' => 'red', 'apricot' => 'orange'])
    ->before(function($value) {
        return $value === 'red';
    })
    ->toArray(); // ['apple' => 'green', 'berry' => 'red']
```

### after

Get elements after a match is found.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'red', 'cherry' => 'red', 'apricot' => 'orange'])
    ->before(function($value, $key) {
        return $value === 'red';
    })
    ->toArray(); // ['cherry' => 'red', 'apricot' => 'orange']
```

The seconds argument is the key.

```php
Pipeline::with(['apple' => 'green', 'berry' => 'red', 'cherry' => 'red', 'apricot' => 'orange'])
    ->before(function($value, $key) {
        return $key === 'berry';
    })
    ->toArray(); // ['cherry' => 'red', 'apricot' => 'orange']
```

Optionally the matched value can be included in the result

```php
Pipeline::with(['apple' => 'green', 'berry' => 'red', 'cherry' => 'red', 'apricot' => 'orange'])
    ->before(function($value) {
        return $value === 'red';
    })
    ->toArray(); // ['berry' => 'red', 'cherry' => 'red', 'apricot' => 'orange']
```

## Sorting

Sorting requires traversing through the iterator to index all elements.

### sort

Create an iterator with sorted elements.

```php
Pipeline::with(["Charlie", "Echo", "Bravo", "Delta", "Foxtrot", "Alpha"])
    ->sorted()
    ->toArray(); // ["Alpha", "Beta", "Charlie", "Delta", "Echo", "Foxtrot"]
```

Instead of using the default sorting, a callback may be passed as user defined comparison function.

```php
Pipeline::with(["Charlie", "Echo", "Bravo", "Delta", "Foxtrot", "Alpha"])
    ->sorted(function($a, $b): int {
        return strlen($a) <=> strlen($b) ?: $a <=> $b;
    })
    ->toArray(); // ["Echo", "Alpha", "Bravo", "Delta", "Charlie", "Foxtrot"]
```

The callback must return < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.

### sortKeys

Create an iterator with sorted elements by key.

```php
Pipeline::with(["Charlie" => "three", "Bravo" => "two", "Delta" => "four", "Alpha" => "one"])
    ->sortedByKeys()
    ->toArray();
    
// ["Alpha" => "one", "Bravo" => "two", "Charlie" => "three", "Delta" => "four"]
```

A callback may be passed as user defined comparison function.

```php
Pipeline::with(["Charlie" => "three", "Bravo" => "two", "Delta" => "four", "Alpha" => "one"])
    ->sortedByKeys(function($a, $b): int {
        return strlen($a) <=> strlen($b) ?: $a <=> $b;
    })
    ->toArray(); 
    
// ["Alpha" => "one", "Bravo" => "two", "Delta" => "four", "Charlie" => "three"]
```

### reverse

Create an iterator with elements in the reversed orderd. The keys are preserved.

```php
Pipeline::with(range(5, 10))
    ->reverse()
    ->toArray(); // [5 => 10, 4 => 9, 3 => 8, 2 => 7, 1 => 6, 0 => 5]
```

## Type handling

### typeCheck

Validate that a value has a specific type using [`type_check`](https://github.com/improved-php-library/type#type_check).
A [`TypeError`](https://php.net/typeerror) is thrown if any element of the iterable doesn't match the type.

```php
Pipeline::with($values)
    ->typeCheck(['int', 'float'])
    ->toArray();
```

As type you may specific any PHP type, a pseudo types like `iterable` or `callable`, as class name or a resource type.
For resources use the resource type, plus "resource", eg `"stream resource"`. 

As second argument, a `Throwable` object may be passed, this is either an `Exception` or `Error`.

The error message may contain up to three sprintf place holders. The first `%s` is replaced with the type of the value.
The second is used for the description of the key. The third is typically not needed, but when specified is
replaced with the given type(s).

```php
Pipeline::with($values)
    ->expectType('int', new \UnexpectedValue('Element %2$s should be an integer, %1$s given'))
    ->toArray();
```

A question mark can be added to a class to accept null, eg `"?string"` is similar to using `["string", "null"]`.

### typeCast

Cast a value to the specific type. This method uses
[`type_cast`](https://github.com/improved-php-library/type#type_cast).

| from     | to                    |                                          |
|----------|-----------------------|------------------------------------------|
| `string` | `int`                 | only numeric strings and < `PHP_INT_MAX` |
| `string` | `float`               | only numeric strings                     |
| `int`    | `bool`                | only 0 or 1                              |
| `int`    | `float`               |                                          |
| `int`    | `string`              |                                          |
| `float`  | `int`                 | if float < `PHP_INT_MAX`                 |
| `float`  | `string`              |                                          |
| `bool`   | `int`                 |                                          |
| `array`  | `object` \ `stdClass` | if array has no numeric keys             |
| `object` | `string`              | if only has `__toString()` method        |
| `object` | `array` \ `iterable`  | only `stdClass` objects                  |
| `null`   | any scalar            |                                          |
| `null`   | `array`               |                                          |
| `null`   | `object` \ `stdClass` |                                          |

If the value can't be cast, a [`TypeError`](https://php.net/typeerror) is thrown. Similar to `typeCheck()` a `Throwable`
with a message may be passed as second argument.

In contrary `typeCheck`, only one type may be specified. A question mark can be added to a class to accept null, eg
`?string` will try to cast everything to a string except `null`.

## Finding

These methods invoke traversing through the iterator and return a single element.

### first

Get the first element.

```php
Pipeline::with(["one", "two", "three"])
    ->first(); // "one"
```

Optionally a `RangeException` can be thrown if the iterable is empty.

### last

Get the last element.

```php
Pipeline::with(["one", "two", "three"])
    ->last(); // "three"
```

### find

Find the first element that matches a condition. Returns `null` if no element is found.

```php
Pipeline::with(["one", "two", "three"])
    ->find(function(string $value): bool {
        return substr($value, 0, 1) === 't';
    }); // "two"
```

It's possible to use the key in this callable.

```php
Pipeline::with(["one" => "uno", "two" => "dos", "three" => "tres"])
    ->find(function(string $value, string $key): bool {
        return substr($key, 0, 1) === 't';
    }); // "dos"
```

### findKey

Find the first element that matches a condition and return the key (rather than the value). Returns `null` if no element
is found.

```php
Pipeline::with(["I" => "one", "II" => "two", "III" => "three"])
    ->find(function(string $value): bool {
        return substr($value, 0, 1) === 't';
    }); // "II"
```

It's possible to use the key in this callable.

```php
Pipeline::with(["one" => "uno", "two" => "dos", "three" => "tres"])
    ->find(function(string $value, string $key): bool {
        return substr($key, 0, 1) === 't';
    }); // "two"
```

### hasAny

Check if any element matches the given condition.

```php
Pipeline::with(["one", "two", "three"])
    ->hasAny(function(string $value): bool {
        return substr($value, 0, 1) === 't';
    }); // true
```

The callback is similar to `find`.

### hasAll

Check if all elements match the given condition.

```php
Pipeline::with(["one", "two", "three"])
    ->hasAny(function(string $value): bool {
        return substr($value, 0, 1) === 't';
    }); // false
```

The callback is similar to `find`.

### hasNone

Check the no element matches the given condition. This is the inverse of `hasAny()`.

```php
Pipeline::with(["one", "two", "three"])
    ->hasNone(function(string $value): bool {
        return substr($value, 0, 1) === 't';
    }); // false
```

The callback is similar to `find`.


### min

Returns the minimal element according to a given comparator.

```php
Pipeline::with([99.7, 24, -7.2, -337, 122.0]))
    ->min(); // -337
```

It's possible to pass a callable for custom logic for comparison.

```php
Pipeline::with([99.7, 24, -7.2, -337, 122.0])
    ->min(function($a, $b) {
        return abs($a) <=> abs($b);
    }); // -7.2
```

### max

Returns the maximal element according to a given comparator.

```php
Pipeline::with([99.7, 24, -7.2, -337, 122.0]))
    ->max(); // 122.0
```

It's possible to pass a callable for custom logic for comparison.

```php
Pipeline::with([99.7, 24, -7.2, -337, 122.0])
    ->max(function($a, $b) {
        return abs($a) <=> abs($b);
    }); // -337
```


## Aggregation

Traverse through all elements and reduce it to a single value.

### count

Returns the number of elements.

```php
Pipeline::with([2, 8, 4, 12]))
    ->count(); // 4
```

### reduce

Reduce all elements to a single value using a callback.

```php
Pipeline::with([2, 3, 4])
    ->reduce(function(int $product, int $value): int {
        return $product * $value;
    }, 1); // 24
```

The third argument is the key

```php
Pipeline::with(['I' => 'one, 'II' => 'two', 'III' => 'three'])
    ->reduce(function(string $list, string $value, string $key): string {
        return $list . sprintf("{%s:%s}", $key, $value);
    }, ''); // "{I:one}{II:two}{III:three}"
```

### sum

Calculate the sum of a numbers. If no elements are present, the result is 0.
 
```php
Pipeline::with([2, 8, 4, 12])
    ->sum(); // 26
```

### average

Calculate the arithmetic mean. If no elements are present, the result is `NAN`.

```php
Pipeline::with([2, 8, 4, 12]))
    ->average(); // 6.5
```

### concat

Concatenate the input elements, separated by the specified delimiter, in encounter order.

This is comparable to [implode](https://php.net/implode) on normal arrays. 

```php
Pipeline::with(["hello", "sweet", "world"])
    ->concat(" - "); // "hello - sweet - world"
```

### stub

The `stub()` method a stub step, which does nothing but can be replaced later using `unstub()`.

    PipelineBuilder stub(string name)
    PipelineBuilder unstub(string name, callable $callable, mixed ...$args) 

_These methods only exists in the pipeline builder._

```php
$blueprint = Pipeline::build()
    ->expectType('string')
    ->stub('process');
    ->sort();
    
// Later
$pipeline = $blueprint
    ->unstub('process', i\iterable_map, i\function_partial(i\string_convert_case, ___, i\STRING_UPPERCASE)));
```

## What are iterators?

Iterators are traversable objects. That means that when you use them in a `foreach` loop, you're not looping through
the properties of the object. Instead the `current()`, `key()` and `valid()` methods are called each time we go through
the loop.

The `current()` method gives the current value, the `key()` method gives the current key and the `valid()` method
checks if we're should continue looping.
  
In the following example we extend `IteratorIterator` to overwrite the `current()` class.

```php
use Improved as i;

class UpperIterator extends IteratorIterator
{
    public function current()
    {
        return i\string_case_convert(parent::current(), i\STRING_UPPERCASE);
    }
};

class NoSpaceIterator extends IteratorIterator
{
    public function current()
    {
        return i\string_replace(parent::current(), " ", "");
    }
};

$data = get_some_data();
$iterator = new NoSpaceIterator(new UpperIterator(new ArrayIterator($data)));
```

At this point nothing is executed. Neither `string_case_convert` or `string_replace`. Only once we loop, these functions
are called.

```php
foreach ($iterator as $cleanValue) {
    echo $cleanValue;
}
```

This will be the same as doing

```php
foreach ($data as $value) {
    $upperValue = i\string_case_convert($value, i\STRING_UPPERCASE);
    $cleanValue = i\string_replace($upper, " ", "");
    
    echo $cleanValue;
}
```

### Difference to working with arrays

When working with arrays, we tend to loop through them for each operation. Look at `array_map`

```php
$upperData = array_map(function($value) {
    return i\string_case_convert($value, i\STRING_UPPERCASE);
}, $data);

$cleanData = array_map(function($value) {
    return i\string_replace($value, i\STRING_UPPERCASE);
}, $upperData);

foreach ($cleanData as $cleanValue) {
    echo $cleanValue;
}
```

Which is similar to

```php
$upperData = [];
$cleanData = [];

foreach ($data as $value) {
    $upperData[] = i\string_case_convert($value, i\STRING_UPPERCASE);
}

foreach ($upperData as $upperValue) {
    $cleanData[] = i\string_replace($upperValue, i\STRING_UPPERCASE);
}

foreach ($cleanData as $cleanValue) {
    echo $cleanValue;
}
```

Of course we could combine these operators an apply them in a simple loop without the use of iterators. However this
couples all that logic. If a method returns all values in upper case, a second and unrelated method (in a different
class) might remove the spaces. For iterators this doesn't matter.

### Iterator keys

With iterators, the key doesn't need to be a string or integer, but can be any type and doesn't need to be unique.

It can very convenient to make the key an array or object and keeping the value a scalar. As such you can do link
operations like case conversion, etc. Another application is to group child objects per parent object.

### Generators

[Generators](http://php.net/generator) are special iterators which are automatically created by PHP when you use the
`yield` syntax. 

```php
function iterable_first_word(iterable $values): Generator
{
    foreach ($values as $key => $value) {
        $word = i\string_before($value, " ");
    
        yield $key => $word;
    }
}
```

PHP 7.2+ is highly optimized to work with generators increasing performance and saving memory. This makes generators
preferable to custom iterators, which can be slow.

#### Unexpected generator behaviour

If you add a `return` statement, the function will still return a Generator object. You can get that result with the
`Generator->getReturn()` method, but this is typically not what's intended.

```php
function get_values(iterable $values)
{
    if (is_array($values)) {
        return array_values($values);
    }

    foreach ($values as $value) {
        yield $value;
    }
}
```

The following code will not work as intended. It will not return an array, but always a `Generator` object.

Also note that the none of the code in the `get_values` function will execute until the we start the loop

```php
function iterable_first_word(iterable $values): Generator
{
    var_dump($values);

    foreach ($values as $key => $value) {
        yield $key => i\string_before($value, " ");
    }
}

$words = iterable_first_word($values);

// Nothing is outputted yet

foreach ($words as $word) { // Now we get the var_dump() as the function is executed till yield 
    // ...
}

```

### Forward-only iterators

Some iterators, including generators, are forward-only iterators, meaning you can only loop through them once.

```php
function numbers_to($count) {
    for ($i = 1; $i <= $count; $i++) {
        yield $i;
    }
}

$oneToTen = numbers_to(10);

foreach ($oneToTen as $number) {
    echo $number;
}

// The following loop will cause an error to be thrown.

foreach ($oneToTen as $number) {
    foo($number);
}
```

This has consequences when using
the `iterable_` functions and `Pipeline` objects. Though this can be overcome using a `PipelineBuilder`. 

_**And now you know :-)**_

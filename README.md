# Jasny Pipeline iterator

[![Build Status](https://travis-ci.org/jasny/iterator-pipeline.svg?branch=master)](https://travis-ci.org/jasny/iterator-pipeline)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/iterator-pipeline/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/iterator-pipeline/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/iterator-pipeline/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/iterator-pipeline/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/iterator-pipeline.svg)](https://packagist.org/packages/jasny/iterator-pipeline)
[![Packagist License](https://img.shields.io/packagist/l/jasny/iterator-pipeline.svg)](https://packagist.org/packages/jasny/iterator-pipeline)

This library support functional-style operations, such as map-reduce transformations on arrays and
[iterators](http://php.net/manual/en/class.iterator.php).


* [SPL iterators](http://php.net/manual/en/spl.iterators.php)
* [Jasny iterators](https://github.com/jasny/iterator)
* [Jasny aggregators](https://github.com/jasny/iterable-functionss)
* [Jasny iterator stream](https://github.com/jasny/iterator-stream)

## Installation

    composer require jasny/iterator-pipeline

## Pipeline methods

#### Chainable methods

**Mapping**
* [`map(callable $callback)`](#map)
* [`mapKeys(callable $callback)`](#mapkeys)
* [`apply(callable $callback)`](#apply)
* [`then(callable $callback)`](#then)
* [`group(callable $callback)`](#group)
* [`flatten()`](#flatten)
* [`project(array $mapping)`](#project)
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
* [`expectType(string|array $type[, string $message])`](#expecttype)

**Sorting**
* [`sort([int|callable $compare[, bool $preserveKeys]])`](#sort)
* [`sortKeys([int|callable $compare])`](#sortkeys)
* [`reverse()`](#reverse)

#### Other methods

**General**
* [`getIterator(): Iterator`](#getiterator)
* [`toArray(): array`](#toarray)

**Finding**
* [`first([bool $required])`](#first)
* [`last([bool $required])`](#last)
* [`find(callable $matcher)`](#find)
* [`min([callable $compare])`](#min)
* [`max([callable $compare])`](#max)

**Aggregation**
* [`count(): int`](#count)
* [`reduce(callable $callback[, mixed $initial])`](#reduce)
* [`sum(): int|float`](#sum)
* [`average(): int|float`](#average)
* [`concat([string $glue]): string`](#concat)

**Output**
* [`output([resource|string $stream[, string $delimiter]])`](#output)
* [`outputCsv([resource|string $stream[, array $headers, [...]]])`](#outputcsv)
* [`outputJson([resource|string $stream[, int $options]])`](#outputjson)

## Example

Consider the following code.

```php
$values = new ArrayIterator($values);
$filteredValues = new CallbackFilterIterator($values, function($value) {
   return is_int($value) && $value > 10;
});
$firstValues = new LimitIterator($values, 0, 10);

foreach ($firstValues as $value) {
    echo $value, "\n";
}
```

This can be rewritten as

```php
use Jasny\IteratorPipeline\Pipeline;

Pipeline::with($values)
    ->filter(function($value) {
        return is_int($value) && $value < 10;
    })
    ->limit(10)
    ->output();
```

## Usage

This library provides Utility methods for creating streams.

`Jasny\IteratorPipeline\Pipeline` takes a `Traversable` as source argument.

The static `pipe()` method also takes an array.

```php
use Jasny\IteratorPipeline\Pipeline;

Pipeline::with([
    new Person("Max", 18),
    new Person("Peter", 23),
    new Person("Pamela", 23)
]);

$dirs = new Pipeline(new \DirectoryIterator('some/path'));
```

In a [SOLID](https://en.wikipedia.org/wiki/SOLID) application, you don't want to use static methods. Instead, use the
`PipelineFactory`.

```php
use Jasny\IteratorPipeline\PipelineFactory;

$factory = new PipelineFactory();

$factory->pipe(['one', 'two', 'three']);
```

### getIterator

The Pipeline implements the [`IteratorAggregate`](https://php.net/iteratoraggregate) interface. This means it's
traversable. Alternatively you can use `getIterator`.

### toArray

Copy the elements of the iterator into an array.

```php
Pipeline::with(["one", "two", "three"])
    ->toArray();
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

```php
Pipeline::with(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->mapKeys(function(string $key): string {
        return subst($key, 0, 1);
    })
    ->toArray(); // ['a' => 'green', 'b' => 'blue', 'c' => 'red']
```

The second argument of the callback is the **value**. _This is different to for other callbacks._

```php
Pipeline::with(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->map(function(string $key, string $value): string {
        return "{$key} ({$value})";
    })
    ->toArray(); // ['apple (green)' => 'green', 'berry (blue)' => 'blue', 'cherry (red)' => 'red']
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
//   ['I' => 'uno', 'II' => 'dos', 'III' => 'tres', 'IV' => 'cuatro', 'V' => 'cinco'],
//   ['I' => 'yi', 'II' => 'er', 'III' => 'san', 'IV' => 'si', 'V' => 'wu'],
//   ['I' => 'één', 'II' => 'twee', 'III' => 'drie', 'IV' => null, 'V' => 'vijf']
// ]
```

If an element doesn't have a specified key, the value will be `null`.

The order of keys of the projected array is always the same as the order of the mapping. The mapping may also be a
numeric array.

Scalar elements and `DateTime` object are ignored.

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
Pipeline::with(['one', 'two', null, 'four', 'null])
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

### expectType

Validate that a value has a specific type using [`expect_type`](https://github.com/jasny/php-functions#expect_type).
Throws an [`UnexpectedValueException`](https://php.net/unexpectedvalueexception).

```php
Pipeline::with($values)
    ->expectType('int')
    ->toArray();
```

An alternative message may be specified as second argument, where the first `%s` is replaced by the key and the second
`%s` (or `%2$s`) by the type.

```php
Pipeline::with($values)
    ->expectType('int', "Value for element '%s' should be an integer, %s given")
    ->toArray();
```


## Sorting

Sorting requires traversing through the iterator to index all elements.

### sorted

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

### sortedByKeys

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
    ->average; // 6.5
```

### concat

Concatenate the input elements, separated by the specified delimiter, in encounter order.

This is comparable to [implode](https://php.net/implode) on normal arrays. 

```php
Pipeline::with(["hello", "sweet", "world"])
    ->concat(" - "); // "hello - sweet - world"
```


## Output

Traverse through the iterator, writing to a stream.

To use it with a [PSR-7 stream](https://www.php-fig.org/psr/psr-7/#13-streams), you need to detach the underlying
resource and pass it to constructor.

```php
Pipeline::with($values)
    ->output($psr7request->getBody()->detach());
```

### output

Output the elements to a stream. The elements should be strings.

```php
Pipeline::with(["one", "two", "three"])
    ->output();
```

Takes a writable stream resource or a file name / uri (string) as first argument. Defaults to `php://output`.

```php
Pipeline::with(["one", "two", "three"])
    ->output('path/to/my/file');
```

Defaults to one element per line, but an alternative delimiter may be specified as second argument.

Uses [`LineOutputStream`](https://github.com/jasny/iterator-stream#line).

### outputCsv

Output the elements to a stream as CSV using [`fputcsv()`](https://php.net/fputcsv). The elements must be arrays.

Takes a writable stream resource or a file name / uri (string) as first argument. Defaults to `php://output`.

```php
$rows = [
    ['one', 'foo', 'red', 22],
    ['two', 'bar', 'green', 42],
    ['three', 'qux', 'blue', -20]
];
    
Pipeline::with($rows)
    ->outputCsv('path/to/my/file.csv');
```

Optionally headers can be specified as second argument.

```php
Pipeline::with($rows)
    ->outputCsv('path/to/my/file.csv', ['number', 'word', 'color', 'integer']);
```

Other CSV properties like the delimiter may also be specified.

The keys of these arrays are ignored. If the elements are objects or associative arrays, use `project()`.

```php
$rows = [
    ['number' => 'one', 'word' => 'foo', 'color' => 'red', 'integer' => 22],
    ['number' => 'two', 'word' => 'bar', 'color' => 'green', 'integer' => 42],
    ['number' => 'three', 'color' => 'blue']
];
    
Pipeline::with($rows)
    ->project(['number', 'word', 'color', 'integer'])
    ->outputCsv('path/to/my/file.csv');
```

Uses [`CsvOutputStream`](https://github.com/jasny/iterator-stream#csv)

### outputJson

Output the elements to a stream as JSON.

Takes a writable stream resource or a file name / uri (string) as first argument. Defaults to `php://output`.

```php
$rows = [
    ['number' => 'one', 'word' => 'foo', 'color' => 'red', 'integer' => 22],
    ['number' => 'two', 'word' => 'bar', 'color' => 'green', 'integer' => 42],
    ['number' => 'three', 'color' => 'blue']
];

Pipeline::with($rows)
    ->outputJson();
```

Elements can be any type of variable that can be cast to JSON.

A binary set with `JSON_*` options, like `JSON_PRETTY_PRINT` can be specified as second argument.

By default the stream is outputted as JSON array. To output a newline delimited JSON stream, add
`JsonOutputStream::OUTPUT_LINES` to the options binary set.

```php
use Jasny\IteratorStream\JsonOutputStream;

$rows = ...

Pipeline::with($rows)
    ->outputJson('php://output', \JSON_PRETTY_PRINT | JsonOutputStream::OUTPUT_LINES);
```

Uses [`JsonOutputStream`](https://github.com/jasny/iterator-stream#json)

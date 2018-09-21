# Jasny Pipeline iterator

[![Build Status](https://travis-ci.org/jasny/pipeline-iterator.svg?branch=master)](https://travis-ci.org/jasny/pipeline-iterator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/pipeline-iterator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/pipeline-iterator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/pipeline-iterator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/pipeline-iterator/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/pipeline-iterator.svg)](https://packagist.org/packages/jasny/pipeline-iterator)
[![Packagist License](https://img.shields.io/packagist/l/jasny/pipeline-iterator.svg)](https://packagist.org/packages/jasny/pipeline-iterator)

This library support functional-style operations, such as map-reduce transformations on
[iterators](http://php.net/manual/en/class.iterator.php).

The `Pipeline` is implements the [builder design pattern](https://sourcemaking.com/design_patterns/builder), wrapping
each `Iterator`. It uses

* [SPL iterators](http://php.net/manual/en/spl.iterators.php)
* [Jasny iterators](https://github.com/jasny/iterator)
* [Jasny aggregators](https://github.com/jasny/aggregators)
* [Jasny iterator stream](https://github.com/jasny/iterator-stream)

## Installation

    composer require jasny/iterator-pipeline

## Pipeline methods

#### Chainable methods

**Mapping**
* [`map(callable $mapper)`](#map)
* [`mapKeys(callable $mapper)`](#mapkeys)
* [`apply(callable $action)`](#apply)
* `forEach(callable $action)` - _alias of `apply()`_
* [`then(callable $callback)`](#then)
* [`group(callable $grouper)`](#group)
* [`flatten()`](#flatten)
* [`project(array $mapping)`](#project)
* [`values()`](#values)
* [`keys()`](#keys)
* [`setKeys(Traversable $keys)`](#setkeys)
* [`flip()`](#flip)

**Filtering**
* [`filter(callable $predicate)`](#filter)
* [`cleanup()`](#cleanup)
* [`unique([callable $grouper])`](#unique)
* [`uniqueKeys()`](#uniquekeys)
* [`limit(int size)`](#limit)
* [`slice(int offset[, int size])`](#slice)
* [`infinete()`](#infinete)
* [`assert(callable $assertion, string message)`](#assert)
* [`assertType(string|array type[, string message])`](#expecttype)

**Sorting**
* [`sort([callable $comparator])`](#sort)
* [`ksort([callable $comparator])`](#ksort)
* [`reverse()`](#reverse)

#### Other methods

**General**
* [`getIterator(): Iterator`](#getiterator)
* [`toArray(): array`](#toarray)

**Finding**
* [`first()`](#first)
* [`find(callable $predicate)`](#find)
* [`min([callable $predicate])`](#min)
* [`max([callable $predicate])`](#max)

**Aggregation**
* [`count(): int`](#count)
* [`reduce(callable $accumulator[, mixed $initial])`](#reduce)
* [`sum(): int|float`](#sum)
* [`average(): int|float`](#average)
* [`concat(string $glue): string`](#concat)

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

Pipeline::pipe($values)
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

Pipeline::pipe([
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
Pipeline::pipe(["one", "two", "three"])
    ->toArray();
```

Uses [`ConcatAggregator`](https://github.com/jasny/aggregator#concataggregator)


## Mapping

### map

Map each element to a value using a callback function.

```php
Pipeline::pipe([3, 2, 2, 3, 7, 3, 6, 5])
    ->map(function(int $i): int {
        return $i * $i;
    });

// [9, 4, 4, 9, 49, 9, 36, 25]
```

The second argument of the callback is the key.

```php
Pipeline::pipe(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->map(function(string $value, string $key): string {
        return "{$value} {$key}";
    });

// ['apple' => 'green apple', 'berry' => 'blue berry', 'cherry' => 'red cherry']
```

Creates a [`MapIterator`](https://github.com/jasny/iterator#mapiterator).

### mapKeys

Map the key of each element to a new key using a callback function.

```php
Pipeline::pipe(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->mapKeys(function(string $key): string {
        return subst($key, 0, 1);
    });

// ['a' => 'green', 'b' => 'blue', 'c' => 'red']
```

The second argument of the callback is the **value**. _This is different to for other callbacks._

```php
Pipeline::pipe(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->map(function(string $key, string $value): string {
        return "{$key} ({$value})";
    });

// ['apple (green)' => 'green', 'berry (blue)' => 'blue', 'cherry (red)' => 'red']
```

Creates a [`MapKeyIterator`](https://github.com/jasny/iterator#mapkeyiterator)

### apply

Apply a callback to each element of an iterator. Any value returned by the callback is ignored.


```php
$persons = [
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
];

Pipeline::pipe($persons)
    ->apply(function(Person $value, string $key): void {
        $value->role = $key;
    });
```

Creates an [`ApplyIterator](https://github.com/jasny/iterator#applyiterator)

### then

The `then()` method can be used for callback that returns a `Generator` or other `Traversable`.

```php
Pipeline::pipe(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->then(function(\Traversable $values): \Generator {
        foreach ($values as $key => $value) {
            yield $key[0] => "$value $key";
        }
    });

// ['a' => 'green apple', 'b' => 'blue berry', 'c' => 'red cherry']
```

It may be used to apply a custom (outer) iterator.

```php
Pipeline::pipe(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red'])
    ->then(function(\Traversable $values): \Iterator {
        return new MyCustomIterator($values);
    });
```

### group

Group elements of an iterator, with the group name as key and an array of elements as value.

```php
Pipeline::pipe(['apple', 'berry', 'cherry', 'apricot'])
    ->group(function(string $value): string {
        return $value[0];
    });

// ['a' => ['apple', 'apricot'], 'b' => ['berry'], 'c' => ['cherry']]
```

The second argument is the key.

```php
Pipeline::pipe(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red', 'apricot' => 'orange'])
    ->group(function(string $value, string $key): string {
        return $key[0];
    });

// ['a' => ['apple' => 'green', 'apricot' => 'orange'], 'b' => ['berry' => 'blue'], 'c' => ['cherry' => 'red']]
```

Uses [`GroupIteratorAggregate`](https://github.com/jasny/iterator#groupiteratoraggregate)

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

Pipeline::pipe($groups)
    ->flatten();

// ['one', 'two', 'three', 'four', 'five', 'six', 'seven']
```
By default the keys are dropped, replaces by an incrementing counter (so as an numeric array). By passing `true` as
second parameters, the keys are remained.

Uses [`FlattenIterator`](https://github.com/jasny/iterator#flatteniterator)

### project

Project each element of an iterator to an associated (or numeric) array. Each element should be an array or object.

For the projection, a mapping `[new key => old key]` must be supplied.

```php
$rows = [
    ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
    ['one' => 'yi', 'two' => 'er', 'three' => 'san', 'four' => 'si', 'five' => 'wu'],
    ['one' => 'één', 'two' => 'twee', 'three' => 'drie', 'five' => 'vijf']
];

Pipeline::pipe($rows)
    ->project(['I' => 'one', 'II' => 'two', 'II' => 'three', 'IV' => 'four']);

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

Creates an [`ProjectionIterator`](https://github.com/jasny/iterator#projectioniterator)

### values

Keep the values, drop the keys. The keys become an incremental number. This is comparable to
[`array_values`](https://php.net/array_values).

```php
Pipeline::pipe(['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro'])
    ->values();
    
// ['uno', 'dos', 'tres', 'cuatro']
```

Creates a [`ValueIterator`](https://github.com/jasny/iterator#valueiterator)

### keys

Use the keys as values. The keys become an incremental number. This is comparable to
[`array_keys`](https://php.net/array_keys).

```php
Pipeline::pipe(['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro'])
    ->keys();
    
// ['one', 'two', 'three', 'four']
```

Creates a [`KeyIterator`](https://github.com/jasny/iterator#keyiterator)

### setKeys

Use another iterator as keys.

```php
Pipeline::pipe(['one', 'two', 'three', 'four'])
    ->setKeys(new \ArrayIterator(['I', 'II', 'III', 'IV']));

// [ 'I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four' ]    
```

The key may be any type and doesn't need to be unique.

The number of elements yielded from the iterator only depends on the number of keys. If there are more keys than
values, the value defaults to `null`. If there are more values than keys, the additional values are not returned.

Creates a [`CombineIterator`](https://github.com/jasny/iterator#combineiterator) using the current iterator as values.

### flip

Use values as keys and visa versa.

```php
Pipeline::pipe(['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro'])
    ->flip();

// ['uno' => 'one', 'dos' => 'two', 'tres' => 'three', 'cuatro' => 'four']
```

Both the value and key may be any type and don't need to be unique.

Creates a [`FlipIterator`](https://github.com/jasny/iterator#flipiterator) using the current iterator as values.


## Filtering

### filter

Eliminate elements based on a criteria.

The callback function is required amd should return a boolean.

```php
Pipeline::pipe([3, 2, 2, 3, 7, 3, 6, 5])
    ->filter(function(int $i): bool {
        return $i % 2 === 0; // is even
    });

// [1 => 2, 2 => 2, 6 => 6]
```

The second argument of the callback is the key.

```php
Pipeline::pipe(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red', 'apricot' => 'orange'])
    ->filter(function(string $value, string $key): bool {
        return $key[0] === 'a';
    });

// ['apple' => 'green', 'apricot' => 'orange']
```

Creates a [`CallbackFilterIterator`](https://php.net/callbackfilteriterator)

### cleanup()

Remove elements thar are `null`.

```php
Pipeline::pipe(['one', 'two', null, 'four', 'null])
    ->cleanup();

// [0 => 'one', 1 => 'two', 3 => 'four']
```

Creates a [`CleanupIterator`](https://github.com/jasny/iterator#cleanupiterator)

### unique

Filter on unique elements.

```php
Pipeline::pipe(['foo', 'bar', 'qux', 'foo', 'zoo'])
    ->unique();

// [0 => 'foo', 1 => 'bar', 2 => qux, 4 => 'zoo']
```

You can pass a callback, which should return a value. Filtering on distinct values will be based on that value.

```php
$persons = [
    new Person("Max", 18),
    new Person("Peter", 23),
    new Person("Pamela", 23)
];

Pipeline::pipe($persons)
    ->unique(function(Person $value): int {
        return $value->age;
    });

// [0 => Person {'name' => "Max", 'age' => 18}, 1 => Person {'name' => "Peter", 'age' => 23}]
```

All values are stored for reference. The callback function can also be used to serialize and hash the value.

```php
Pipeline::pipe($persons)
    ->unique(function(Person $value): int {
        return hash('sha256', serialize($value));
    });
});
```

The seconds argument is the key.

```php
Pipeline::pipe(['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red', 'apricot' => 'orange'])
    ->unique(function(string $value, string $key): string {
        return $key[0];
    });

// ['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red']
```

Uses strict comparison (`===`), so '10' and 10 won't match.

Creates a [`UniqueIterator`](https://github.com/jasny/iterator#uniqueiterator)

### uniqueKeys

The keys of an iterator don't have to be unique (and don't have to be a scalar). This is unlike an associated array.

```php
$someGenerator = function($max) {
    for ($i = 0; $i < $max; $i++) {
        $key = substr(md5((string)$i), 0, 1); // char [0-9a-f]
        yield $key => $i;
    }
};

Pipeline::pipe($someGenerator(1000))
    ->uniqueKeys();

// ['c' => 0, 'e' => 3, 'a' => 4, 1 => 6, 8 => 7, 4 => 9, 'd' => 10, 6 => 11 9 => 15 7 => 17,
//     3 => 21, 'b' => 22, 0 => 27, 'f' => 44, 2 => 51, 5 => 91]
```

Creates a [`UniqueIterator`](https://github.com/jasny/iterator#uniqueiterator) to filter out duplicate keys.

### limit

Get the first only elements of an iterator.

```php
Pipeline::pipe([3, 2, 2, 3, 7, 3, 6, 5])
    ->limit(5);
```

Creates a [`LimitIterator`](https://php.net/limititerator)

### slice

Get a limited subset of the elements using an offset.

```php
Pipeline::pipe([3, 2, 2, 3, 7, 3, 6, 5])
    ->slice(3);
```

You may also specify a limit.

```php
Pipeline::pipe([3, 2, 2, 3, 7, 3, 6, 5])
    ->slice(3, 2);
```

Creates a [`LimitIterator`](https://php.net/limititerator)

### infinete

Infinitely iterate over the iterator.

```php
Pipeline::pipe(range(0, 10))
    ->infinete();
```

_This method can easily create an infinete loop, crashing your app._

Creates an [`InfiniteIterator`](https://php.net/infiniteiterator)

### assert

Validate a value using a callback. Throws an [`UnexpectedValueException`](https://php.net/unexpectedvalueexception)
if the callback returns `false`.

```php
Pipeline::pipe($values)
    ->assert(
        function($value): bool {
            return is_int($value) && $value > 0;
        },
        "Value for element '%s' must be a positive integer"
    );
```

The message must be specified as second argument, where `%s` is replaced by the key. The `%s` must be omited if the
key is not a scalar.

An alterative method to assert values is to use `apply`. This give greater freedom, like customizing the message
based on the value or throwing a custom exception.

Creates an [`AssertIterator`](https://github.com/jasny/iterator#assertiterator)

### assertType

Validate that a value has a specific type using [`expect_type`](https://github.com/jasny/php-functions#expect_type).
Throws an [`UnexpectedValueException`](https://php.net/unexpectedvalueexception).

```php
Pipeline::pipe($values)
    ->assertType('int');
```

An alternative message may be specified as second argument, where the first `%s` is replaced by the key and the second
`%s` (or `%2$s`) by the type.

```php
Pipeline::pipe($values)
    ->assertType('int', "Value for element '%s' should be an integer, %s given");
```

Creates an [`AssertTypeIterator`](https://github.com/jasny/iterator#asserttypeiterator)


## Sorting

Sorting requires traversing through the iterator to index all elements.

### sort

Create an iterator with sorted elements.

```php
Pipeline::pipe(["Charlie", "Echo", "Bravo", "Delta", "Foxtrot", "Alpha"])
    ->sort();
    
// ["Alpha", "Beta", "Charlie", "Delta", "Echo", "Foxtrot"]
```

Instead of using the default sorting, a callback may be passed as user defined comparison function.

```php
Pipeline::pipe(["Charlie", "Echo", "Bravo", "Delta", "Foxtrot", "Alpha"])
    ->sort(function($a, $b): int {
        return strlen($a) <=> strlen($b) ?: $a <=> $b;
    });
    
// ["Echo", "Alpha", "Bravo", "Delta", "Charlie", "Foxtrot"]
```

The callback must return < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.

Uses [`SortIteratorAggregate`](https://github.com/jasny/iterator#sortiteratoraggregate)

### ksort

Create an iterator with sorted elements by key.

```php
Pipeline::pipe(["Charlie" => "three", "Bravo" => "two", "Delta" => "four", "Alpha" => "one"])
    ->ksort();
    
// ["Alpha" => "one", "Bravo" => "two", "Charlie" => "three", "Delta" => "four"]
```

A callback may be passed as user defined comparison function.

```php
Pipeline::pipe(["Charlie" => "three", "Bravo" => "two", "Delta" => "four", "Alpha" => "one"])
    ->ksort(function($a, $b): int {
        return strlen($a) <=> strlen($b) ?: $a <=> $b;
    });

// ["Alpha" => "one", "Bravo" => "two", "Delta" => "four", "Charlie" => "three"]
```

Uses [`SortKeyIteratorAggregate`](https://github.com/jasny/iterator#sortkeyiteratoraggregate)

### reverse

Create an iterator with elements in the reversed orderd. The keys are preserved.

```php
Pipeline::pipe(range(5, 10))
    ->reverse();

// [5 => 10, 4 => 9, 3 => 8, 2 => 7, 1 => 6, 0 => 5]
```

Uses [`ReverseIteratorAggregate`](https://github.com/jasny/iterator#reverseiteratoraggregate)


## Finding

These methods invoke traversing through the iterator and return a single element.

### first

Get the first element.

```php
Pipeline::pipe(["one", "two", "three"])
    ->first();

// "one"
```

Uses [`FirstAggregator`](https://github.com/jasny/aggregator#firstaggregator)

### find

Find the first element that matches a condition. Returns `null` if no element is found.

```php
Pipeline::pipe(["one", "two", "three"])
    ->find(function(string $value): bool {
        return substr($value, 0, 1) === 't';
    });

// "two"
```

It's possible to use the key in this callable.

```php
Pipeline::pipe(["one" => "uno", "two" => "dos", "three" => "tres"])
    ->find(function(string $value, string $key): bool {
        return substr($key, 0, 1) === 't';
    });

// "dos"
```

Uses [`FirstAggregator`](https://github.com/jasny/aggregator#firstaggregator)

### min

Returns the minimal element according to a given comparator.

```php
Pipeline::pipe([99.7, 24, -7.2, -337, 122.0]))
    ->min();

// -337
```

It's possible to pass a callable for custom logic for comparison.

```php
Pipeline::pipe([99.7, 24, -7.2, -337, 122.0])
    ->min(function($a, $b) {
        return abs($a) <=> abs($b);
    });

// -7.2
```

Uses [`MinAggregator`](https://github.com/jasny/aggregator#minaggregator)

### max

Returns the maximal element according to a given comparator.

```php
Pipeline::pipe([99.7, 24, -7.2, -337, 122.0]))
    ->max();
    
// 122.0
```

It's possible to pass a callable for custom logic for comparison.

```php
Pipeline::pipe([99.7, 24, -7.2, -337, 122.0])
    ->max(function($a, $b) {
        return abs($a) <=> abs($b);
    });

// -337
```

Uses [`MaxAggregator`](https://github.com/jasny/aggregator#maxaggregator)


## Aggregation

Traverse through all elements and reduce it to a single value.

### count

Returns the number of elements.

```php
Pipeline::pipe([2, 8, 4, 12]))
    ->count();
    
// 4
```

Uses [`CountAggregator`](https://github.com/jasny/aggregator#countaggregator)

### reduce

Reduce all elements to a single value using a callback.

```php
Pipeline::pipe([2, 3, 4])
    ->reduce(function(int $product, int $value): int {
        return $product * $value;
    }, 1);

// 24
```

Uses [`ReduceAggregator`](https://github.com/jasny/aggregator#reduceaggregator)

### sum

Calculate the sum of a numbers. If no elements are present, the result is 0.
 
```php
Pipeline::pipe([2, 8, 4, 12])
    ->sum();
    
// 26
```

Uses [`SumAggregator`](https://github.com/jasny/aggregator#sumaggregator)

### average

Calculate the arithmetic mean. If no elements are present, the result is `NAN`.

```php
Pipeline::pipe([2, 8, 4, 12]))
    ->average;
    
// 6.5
```

Uses [`AverageAggregator`](https://github.com/jasny/aggregator#averageaggregator)

### concat

Concatenate the input elements, separated by the specified delimiter, in encounter order.

This is comparable to [implode](https://php.net/implode) on normal arrays. 

```php
Pipeline::pipe(["hello", "sweet", "world"])
    ->concat(" - ");
    
// "hello - sweet - world"
```

Uses [`ConcatAggregator`](https://github.com/jasny/aggregator#concataggregator)


## Output

Traverse through the iterator, writing to a stream.

To use it with a [PSR-7 stream](https://www.php-fig.org/psr/psr-7/#13-streams), you need to detach the underlying
resource and pass it to constructor.

```php
Pipeline::pipe($values)
    ->output($psr7request->getBody()->detach());
```

### output

Output the elements to a stream. The elements should be strings.

```php
Pipeline::pipe(["one", "two", "three"])
    ->output();
```

Takes a writable stream resource or a file name / uri (string) as first argument. Defaults to `php://output`.

```php
Pipeline::pipe(["one", "two", "three"])
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
    
Pipeline::pipe($rows)
    ->outputCsv('path/to/my/file.csv');
```

Optionally headers can be specified as second argument.

```php
Pipeline::pipe($rows)
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
    
Pipeline::pipe($rows)
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

Pipeline::pipe($rows)
    ->outputJson();
```

Elements can be any type of variable that can be cast to JSON.

A binary set with `JSON_*` options, like `JSON_PRETTY_PRINT` can be specified as second argument.

By default the stream is outputted as JSON array. To output a newline delimited JSON stream, add
`JsonOutputStream::OUTPUT_LINES` to the options binary set.

```php
use Jasny\IteratorStream\JsonOutputStream;

$rows = ...

Pipeline::pipe($rows)
    ->outputJson('php://output', \JSON_PRETTY_PRINT | JsonOutputStream::OUTPUT_LINES);
```

Uses [`JsonOutputStream`](https://github.com/jasny/iterator-stream#json)

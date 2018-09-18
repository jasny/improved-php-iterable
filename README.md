Jasny Aggregator
===

[![Build Status](https://travis-ci.org/jasny/aggregator.svg?branch=master)](https://travis-ci.org/jasny/aggregator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/aggregator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/aggregator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/aggregator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/aggregator/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/aggregator.svg)](https://packagist.org/packages/jasny/aggregator)
[![Packagist License](https://img.shields.io/packagist/l/jasny/aggregator.svg)](https://packagist.org/packages/jasny/aggregator)

Traverse over elements, aggregating values, returning a single value.

Installation
---

    composer require jasny/aggregator

Usage
--- 

**Array**
* [ArrayAggregator](#arrayaggregator)
* [CountAggregator](#countaggregator)

**Reduce**
* [ReduceAggregator](#reduceaggregator)
* [SumAggregator](#sumaggregator)
* [AverageAggregator](#averageaggregator)
* [ConcatAggregator](#concataggregator)

**Find**
* [FirstAggregator](#firstaggregator)
* [MinAggregator](#minaggregator)
* [MaxAggregator](#minaggregator)


### ArrayAggregator

Aggregator that accumulates the input elements into a new array.

```php
$aggregate = ArrayAggregator(\ArrayIterator([2, 8, 4, 12]));
$array = $aggregate();
```

### CountAggregator

Aggregator that produces a count.

```php
$aggregate = CountAggregator(\ArrayIterator([2, 8, 4, 12]));
$count = $aggregate(); // 4
```

### ReduceAggregator

Reduce all elements to a single value using a callback.

```php
$aggregate = ReduceAggregator(
    \ArrayIterator([2, 3, 4]),
    function(int $product, int $value): int {
        return $product * $value;
    },
    1
);

$value = $aggregate(); // 24
```

### SumAggregator

Aggregator that produces the sum of a numbers. If no elements are present, the result is 0.
 
```php
$aggregate = AverageAggregator(\ArrayIterator([2, 8, 4, 12]));
$average = $aggregate(); // 26
```

### AverageAggregator

Aggregator that produces the arithmetic mean. If no elements are present, the result is `NAN`.

```php
$aggregate = AverageAggregator(\ArrayIterator([2, 8, 4, 12]));
$average = $aggregate(); // 6.5
```

### ConcatAggregator

Concatenates the input elements, separated by the specified delimiter, in encounter order.

This is comparable to [join](https://php.net/join) on normal arrays. 

```php
$aggregate = ConcatAggregator(\ArrayIterator(["hello", "sweet", "world"]), " - ");
$sentence = $aggregate(); // "hello - sweet - world"
```

### FirstAggregator

Get the first element.

```php
$aggregate = FirstAggregator(\ArrayIterator(["one", "two", "three"]));
$value = $aggregate(); // "one"
```

Alternatively, pass a callable and get the first element that matches a condition will be returned.
Returns null if no element is found.

```php
$aggregate = FirstAggregator(
    \ArrayIterator(["one", "two", "three"]),
    function(string $value): bool {
        return substr($value, 0, 1) === 't';
    }
);
$value = $aggregate(); // "two"
```

It's possible to use the key in this callable.

```php
$aggregate = FirstAggregator(
    \ArrayIterator(["one" => "uno", "two" => "dos", "three" => "tres"]),
    function(string $value, string $key): bool {
        return substr($key, 0, 1) === 't';
    }
);
$value = $aggregate(); // "dos"
```

### MinAggregator

Aggregator that produces the minimal element according to a given comparator.

```php
$aggregate = MinAggregator(\ArrayIterator([99.7, 24, -7.2, -337, 122.0]));
$min = $aggregate(); // -337
```

It's possible to pass a callable for custom logic for comparison.

```php
$aggregate = MinAggregator(
    \ArrayIterator([99.7, 24, -7.2, -337, 122.0]),
    function($a, $b) {
        return abs($a) <=> abs($b);
    }
);

$min = $aggregate(); // -7.2
```

### MaxAggregator

Aggregator that produces the maximal element according to a given comparator.

```php
$aggregate = MaxAggregator(\ArrayIterator([99.7, 24, -7.2, -337, 122.0]));
$max = $aggregate(); // 122.0
```

It's possible to pass a callable for custom logic for comparison.

```php
$aggregate = MaxAggregator(
    \ArrayIterator([99.7, 24, -7.2, -337, 122.0]),
    function($a, $b) {
        return abs($a) <=> abs($b);
    }
);

$max = $aggregate(); // -337
```

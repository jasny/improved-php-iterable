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

### ArrayAggregator

Aggregator that accumulates the input elements into a new array.

```php
$aggregate = ArrayAggregator(\ArrayIterator([2, 8, 4, 12]);
$array = $aggregate();
```

### CountAggregator

Aggregator that produces a count.

```php
$aggregate = CountAggregator(\ArrayIterator([2, 8, 4, 12]);
$count = $aggregate(); // 4
```

### SumAggregator

Aggregator that produces the sum of a numbers. If no elements are present, the result is 0.
 
```php
$aggregate = AverageAggregator(\ArrayIterator([2, 8, 4, 12]);
$average = $aggregate(); // 26
```

### AverageAggregator

Aggregator that produces the arithmetic mean. If no elements are present, the result is `NAN`.

```php
$aggregate = AverageAggregator(\ArrayIterator([2, 8, 4, 12]);
$average = $aggregate(); // 6.5
```

### MinAggregator

Aggregator that produces the minimal element according to a given comparator.

```php
$aggregate = MinAggregator(\ArrayIterator([99.7, 24, -7.2, -337, 122.0]);
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
$aggregate = MaxAggregator(\ArrayIterator([99.7, 24, -7.2, -337, 122.0]);
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

### ConcatAggregator

Concatenates the input elements, separated by the specified delimiter, in encounter order.

This is comparable to [join](https://php.net/join) on normal arrays. 

```php
$aggregate = ConcatAggregator(\ArrayIterator(["hello", "sweet", "world"], " - ");
$sentence = $aggregate(); // "hello - sweet - world"
```

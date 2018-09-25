# Jasny Iterable functions

[![Build Status](https://travis-ci.org/jasny/iterator-functions.svg?branch=master)](https://travis-ci.org/jasny/iterator-functions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/iterator-functions/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/iterator-functions/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/iterator-functions/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/iterator-functions/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/iterator-functions.svg)](https://packagist.org/packages/jasny/iterator-functions)
[![Packagist License](https://img.shields.io/packagist/l/jasny/iterator-functions.svg)](https://packagist.org/packages/jasny/iterator-functions)

Functions for arrays, Iterators and other Traversable objects.

These functions are different from their `array_*` counterparts, as they work with any kind of iterable rather than just
arrays.

Functions that do no aggregate to a single value, return a [`Generator`](http://php.net/generator) by using the `yield`
syntax. This means you use these functions to add logic, without traversing (looping) through the values. PHP 7.2 is
highly optimized to work with Generators increasing performance and saving memory.

## Installation

    composer require jasny/iterable-functions


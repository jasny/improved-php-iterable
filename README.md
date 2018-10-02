![improved PHP library](https://user-images.githubusercontent.com/100821/46372249-e5eb7500-c68a-11e8-801a-2ee57da3e5e3.png)

# IPL iterable

[![Build Status](https://travis-ci.org/improved-php-library/iterable.svg?branch=master)](https://travis-ci.org/improved-php-library/iterable)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/improved-php-library/iterable/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/improved-php-library/iterable/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/improved-php-library/iterable/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/improved-php-library/iterable/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/improved-php-library/iterable.svg)](https://packagist.org/packages/improved-php-library/iterable)
[![Packagist License](https://img.shields.io/packagist/l/improved-php-library/iterable.svg)](https://packagist.org/packages/improved-php-library/iterable)

Functions for arrays, Iterators and other Traversable objects.

These functions are different from their `array_*` counterparts, as they work with any kind of iterable rather than just
arrays.

Functions that do no aggregate to a single value, return a [`Generator`](http://php.net/generator) by using the `yield`
syntax. This means you use these functions to add logic, without traversing (looping) through the values. PHP 7.2+ is
highly optimized to work with Generators increasing performance and saving memory.

## Installation

    composer require ipl/iterable

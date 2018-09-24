<?php

declare(strict_types=1);

namespace Jasny;

use Jasny\expect_type;

/**
 * Invoke the aggregator.
 *
 * @param iterable $iterable
 * @param string   $glue
 * @return string
 * @throws \UnexpectedValueException if not all values can be cast to a string
 */
function iterable_concat(iterable $iterable, string $glue = ''): string
{
    $string = "";

    foreach ($iterable as $item) {
        expect_type($item, 'stringable', \UnexpectedValueException::class);

        $string .= $item . $glue;
    }

    return $glue === "" ? $string : substr($string, 0, -1 * strlen($glue));
}

<?php declare(strict_types=1);

namespace Improved;

/**
 * Concatenate all elements into a single string.
 *
 * @param iterable $iterable
 * @param string   $glue
 * @return string
 */
function iterable_concat(iterable $iterable, string $glue = ''): string
{
    $string = "";

    foreach ($iterable as $item) {
        $string .= $item . $glue;
    }

    return $glue === "" ? $string : substr($string, 0, -1 * strlen($glue));
}

<?php

require_once __DIR__ . '/../vendor/autoload.php';

$userFunctions = get_defined_functions()['user'];
$functions = [];

foreach ($userFunctions as $function) {
    if (stripos($function, 'ipl\\iterable_') === 0) {
        $functions[] = $function;
    }
}

$code = ["<?php\n\n// phpcs:ignoreFile\n\ndeclare(strict_types=1);\n\nnamespace Ipl;\n"];

foreach ($functions as $function) {
    $fnName = substr($function, 4);
    $code[] = sprintf('const %1$s = "Ipl\\\\%1$s";', $fnName);
}

file_put_contents(dirname(__DIR__) . '/src/functions-const.php', join("\n", $code) . "\n");

echo "Created functions-const.php\n";


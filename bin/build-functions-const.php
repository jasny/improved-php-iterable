<?php declare(strict_types=1);

// File might be broken, so clear before autoload
file_put_contents(dirname(__DIR__) . '/src/functions-const.php', '');

require_once __DIR__ . '/../vendor/autoload.php';

const FUNCTION_PREFIX = 'iterable_';

$userFunctions = get_defined_functions()['user'];
$functions = [];

foreach ($userFunctions as $function) {
    if (stripos($function, 'improved\\' . FUNCTION_PREFIX) === 0) {
        $functions[] = $function;
    }
}

$code = ["<?php declare(strict_types=1);\n\n/** @ignoreFile */\n// phpcs:ignoreFile\n\nnamespace Improved;\n"];

foreach ($functions as $function) {
    $fnName = substr($function, strlen('improved\\'));
    $code[] = "/** @ignore */\n" . sprintf('const %1$s = "Improved\\\\%1$s";', $fnName);
}

file_put_contents(dirname(__DIR__) . '/src/functions-const.php', join("\n", $code) . "\n");

echo "Created functions-const.php\n";

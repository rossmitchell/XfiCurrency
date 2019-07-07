<?php declare(strict_types=1);
if (!\function_exists('xdebug_set_filter')) {
    return;
}

$directory = dirname(__DIR__) . '/src/';

\xdebug_set_filter(
    \XDEBUG_FILTER_CODE_COVERAGE,
    \XDEBUG_PATH_WHITELIST,
    [
        $directory
    ]
);

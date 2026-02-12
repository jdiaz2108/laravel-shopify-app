<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector;
use Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->withPreparedSets(
        codingStyle: true,
        privatization: true,
        earlyReturn: true,
        rectorPreset: true,

    )
    ->withAttributesSets(symfony: true, doctrine: true)
    ->withPhpSets(php84: true)
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0)
    ->withSkip([
        FunctionFirstClassCallableRector::class,
        EncapsedStringsToSprintfRector::class,
        ArrayToFirstClassCallableRector::class => [
            __DIR__ . '/routes',
        ],
        StaticCallOnNonStaticToInstanceCallRector::class => [
            __DIR__ . '/routes',
        ],
    ]);

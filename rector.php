<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/DependencyInjection',
        __DIR__ . '/Exception',
        __DIR__ . '/Service',
        __DIR__ . '/Tests',
        __DIR__ . '/Translator',
    ])

    ->withPhpSets()
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ]);

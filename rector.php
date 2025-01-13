<?php

declare(strict_types=1);
use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withParallel()
    ->withPreparedSets(codeQuality: true)
    ->withPhpSets(php82: true)
    ->withPaths([
        __DIR__.'/DependencyInjection',
        __DIR__.'/Exception',
        __DIR__.'/Service',
        __DIR__.'/Tests',
        __DIR__.'/Translator',
    ])
    ->withSets([
        SymfonySetList::SYMFONY_62,
    ]);

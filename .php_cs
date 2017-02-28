<?php

$finder =  PhpCsFixer\Finder::create()
    ->exclude('build')
    ->exclude('cache')
    ->exclude('var')
    ->exclude('vendor')
    ->in(__DIR__);

$config = PhpCsFixer\Config::create();
$config
    ->setRules([
        '@Symfony' => true
    ])
    ->setFinder($finder);

return $config;

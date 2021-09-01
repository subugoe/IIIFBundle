<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('build')
    ->exclude('cache')
    ->exclude('var')
    ->exclude('vendor')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
$config
    ->setRules([
        '@Symfony' => true,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private'
            ]
        ],
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);

return $config;

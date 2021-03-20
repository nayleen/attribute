<?php

declare(strict_types = 1);

// https://mlocati.github.io/php-cs-fixer-configurator/#version:2.18.2|configurator
return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@PSR12' => true,
            'blank_line_before_statement' => [
                'statements' => [
                    'return',
                ],
            ],
            'cast_spaces' => [
                'space' => 'single',
            ],
            'declare_equal_normalize' => [
                'space' => 'single',
            ],
            'phpdoc_no_empty_return' => false,
            'php_unit_test_case_static_method_calls' => [
                'call_type' => 'self'
            ],
            'php_unit_method_casing' => [
                'case' => 'snake_case',
            ],
            'ordered_class_elements' => [
                'order' => [
                    'use_trait',
                    'property_private',
                    'property_protected',
                    'property_public',
                    'constant_private',
                    'constant_protected',
                    'constant_public',
                    'construct',
                    'destruct',
                    'method_public_static',
                    'phpunit',
                    'method_private',
                    'method_protected',
                    'method_public',
                    'magic',
                ],
                'sort_algorithm' => 'alpha',
            ],
        ]
    )
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('tests')
            ->exclude('vendor')
            ->in(__DIR__)
    );

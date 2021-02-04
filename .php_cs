<?php

declare(strict_types = 1);

// https://mlocati.github.io/php-cs-fixer-configurator/#version:2.18.2|configurator
return (new PhpCsFixer\Config())
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
            ],
        ]
    )
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in(__DIR__)
    );

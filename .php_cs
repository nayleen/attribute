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
            'no_unreachable_default_argument_value' => true
        ]
    )
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in(__DIR__)
    );

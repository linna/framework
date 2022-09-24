<?php

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'no_whitespace_in_blank_line' => true,
        'return_type_declaration' => true,
        'native_function_invocation' => ['include' => ['@all']],
        'phpdoc_align' => true,
        'phpdoc_separation' => true,
        'phpdoc_line_span' => ['property' => 'single', 'const' => 'single']
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('vendor')
        ->in(__DIR__)
    );
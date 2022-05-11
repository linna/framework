<?php

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'no_whitespace_in_blank_line' => true,
        'return_type_declaration' => true,
        'native_function_invocation' => ['include' => ['@all']],
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('vendor')
        ->in(__DIR__)
    );
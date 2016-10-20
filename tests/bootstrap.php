<?php

include dirname(__DIR__).'/src/Linna/Autoloader.php';

function call_autoloader()
{
    $loader = new \Linna\Autoloader();
    $loader->register();

    $loader->addNamespaces([
        ['Linna', dirname(__DIR__) . '/src/Linna'],
        ['Linna', dirname(__DIR__) . '/src/Linna/Shared'],
        ['Linna\FOO', __DIR__.'/FOO'],
        ['Linna\Auth', dirname(__DIR__) . '/src/Linna/Auth'],
        ['Linna\DI', dirname(__DIR__) . '/src/Linna/DI'],
        ['Linna\Database', dirname(__DIR__) . '/src/Linna/Database'],
        ['Linna\DataMapper', dirname(__DIR__) . '/src/Linna/DataMapper'],
        ['Linna\Http', dirname(__DIR__) . '/src/Linna/Http'],
        ['Linna\Mvc', dirname(__DIR__) . '/src/Linna/Mvc'],
        ['Linna\Session', dirname(__DIR__) . '/src/Linna/Session'],
    ]);
}

call_autoloader();

<?php

//composer autorload
include dirname(__DIR__).'/vendor/autoload.php';

//linna autoload
include dirname(__DIR__).'/src/Linna/Autoloader.php';

function call_autoloader()
{
    $loader = new \Linna\Autoloader();
    $loader->register();

    $loader->addNamespaces([
        ['Linna', dirname(__DIR__) . '/src/Linna'],
        ['Linna\FOO', __DIR__.'/FOO'],
        ['Linna\Auth', dirname(__DIR__) . '/src/Linna/Auth'],
        ['Linna\DI', dirname(__DIR__) . '/src/Linna/DI'],
        ['Linna\Storage', dirname(__DIR__) . '/src/Linna/Storage'],
        ['Linna\DataMapper', dirname(__DIR__) . '/src/Linna/DataMapper'],
        ['Linna\Http', dirname(__DIR__) . '/src/Linna/Http'],
        ['Linna\Mvc', dirname(__DIR__) . '/src/Linna/Mvc'],
        ['Linna\Session', dirname(__DIR__) . '/src/Linna/Session'],
    ]);
}

call_autoloader();

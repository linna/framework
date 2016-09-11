<?php

include __DIR__.'/Linna/Autoloader.php';

$loader = new \Linna\Autoloader();
$loader->register();

$loader->addNamespaces([
    ['Linna', __DIR__ . '/Linna'],
    ['Linna\Auth', __DIR__ . '/Linna/Auth'],
    ['Linna\DI', __DIR__ . '/Linna/DI'],
    ['Linna\Database', __DIR__ . '/Linna/Database'],
    ['Linna\Http', __DIR__ . '/Linna/Http'],
    ['Linna\Mvc', __DIR__ . 'Linna/Mvc'],
    ['Linna\Session', __DIR__ . 'Linna/Session'],
]);
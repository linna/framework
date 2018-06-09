<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna;

/**
 * PSR-4 Autoloader.
 *
 * An example of a general-purpose implementation that includes the optional
 * functionality of allowing multiple base directories for a single namespace
 * prefix.
 *
 * Given a foo-bar package of classes in the file system at the following
 * paths ...
 *
 *     /path/to/packages/foo-bar/
 *         src/
 *             Baz.php             # Foo\Bar\Baz
 *             Qux/
 *                 Quux.php        # Foo\Bar\Qux\Quux
 *         tests/
 *             BazTest.php         # Foo\Bar\BazTest
 *             Qux/
 *                 QuuxTest.php    # Foo\Bar\Qux\QuuxTest
 *
 * ... add the path to the class files for the \Foo\Bar\ namespace prefix
 * as follows:
 *
 *      <?php
 *      // instantiate the loader
 *      $loader = new \Example\Psr4AutoloaderClass;
 *
 *      // register the autoloader
 *      $loader->register();
 *
 *      $nm = [
 *          ['Foo\Bar', '/path/to/packages/foo-bar/src'],
 *          ['Foo\Bar', '/path/to/packages/foo-bar/tests']
 *      ];
 *
 *      // register the base directories for the namespace prefix
 *      $loader->addNamespaces($nm);
 *
 *
 * The following line would cause the autoloader to attempt to load the
 * \Foo\Bar\Qux\Quux class from /path/to/packages/foo-bar/src/Qux/Quux.php:
 *
 *      <?php
 *      new \Foo\Bar\Qux\Quux;
 *
 * The following line would cause the autoloader to attempt to load the
 * \Foo\Bar\Qux\QuuxTest class from /path/to/packages/foo-bar/tests/Qux/QuuxTest.php:
 *
 *      <?php
 *      new \Foo\Bar\Qux\QuuxTest;
 */
class Autoloader
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = [];

    /**
     * Register loader with SPL autoloader stack.
     *
     * @return bool
     */
    public function register(): bool
    {
        return spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Unregister loader with SPL autoloader stack.
     *
     * @return bool
     */
    public function unregister(): bool
    {
        return spl_autoload_unregister([$this, 'loadClass']);
    }

    /**
     * Adds a base directory for a namespace prefix, accept an array of namespaces
     * Utilize this for prevente multiple addNamespace() calls.
     *
     * @param array $namespaces The namespace prefix array.
     */
    public function addNamespaces(array $namespaces): void
    {
        //loop for add single namespace
        foreach ($namespaces as $namespace) {

            // normalize namespace prefix
            $prefix = trim($namespace[0], '\\');

            // normalize the base directory with a trailing separator
            $baseDir = rtrim($namespace[1], DIRECTORY_SEPARATOR).'/';

            //add namespace
            $this->prefixes[$prefix] = $baseDir;
        }
    }

    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     *
     * @return bool True on success, false on
     *              failure.
     */
    public function loadClass(string $class): bool
    {
        $arrayClass = explode('\\', $class);

        $arrayPrefix = [];

        while (count($arrayClass)) {
            $arrayPrefix[] = array_shift($arrayClass);

            $prefix = implode('\\', $arrayPrefix);
            $relativeClass = implode('\\', $arrayClass);

            // try to load a mapped file for the prefix and relative class
            if ($this->loadMappedFile($prefix, $relativeClass)) {
                return true;
            }
        }

        // never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix        The namespace prefix.
     * @param string $relativeClass The relative class name.
     *
     * @return bool Boolean false there are any base directories for namespace prefix or file,
     *              true on success.
     */
    private function loadMappedFile(string $prefix, string $relativeClass): bool
    {
        // are there any base directories for this namespace prefix?
        if (!isset($this->prefixes[$prefix])) {
            return false;
        }

        // replace namespace separators with directory separators
        // in the relative class name, append with .php
        $file = $this->prefixes[$prefix].str_replace('\\', '/', $relativeClass).'.php';

        // if the mapped file exists, require it
        if (file_exists($file)) {
            require $file;

            // yes, we're done
            return true;
        }

        //Unable to find class in file.
        return false;
    }
}

<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\Storage;

class Storage
{
    private static $adapters = array();
    
    /**
     * Forbids the object instance
     *
     */
    public function __construct()
    {
        //private for access to class only via static
    }
    
    /**
     * Forbids the object clone
     *
     */
    private function __clone()
    {
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     * @param mixed $adapter
     */
    public function __set($name, $adapter)
    {
        self::$adapters[$name] = $adapter;
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     */
    public function __get($name)
    {
        if (array_key_exists($name, self::$adapters)) {
            return self::$adapters[$name];
        }
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     */
    public function __unset($name)
    {
        unset(self::$adapters[$name]);
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     */
    public function __isset($name)
    {
        return isset(self::$adapters[$name]);
    }
}

<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\DI;

class DIContainer
{
    private $container = array();
    
    /**
     * Constructor
     *
     */
    public function __construct()
    {
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     * @param mixed $resolver
     */
    public function __set($name, $resolver)
    {
        $this->container[$name] = $resolver;
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     * @return mixed Element stored in container or false
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->container)) {
            return $this->container[$name];
        }
        
        return false;
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     */
    public function __unset($name)
    {
        unset($this->container[$name]);
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     */
    public function __isset($name)
    {
        return isset($this->container[$name]);
    }
}

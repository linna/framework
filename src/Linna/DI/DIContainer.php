<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\DI;

/**
 * Dependency Injection Container
 *
 */
class DIContainer
{
    /**
     * @var array $container Callback storage
     */
    private $container;
    
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->container = array();
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     * @param mixed $resolver
     */
    public function __set(string $name, callable $resolver)
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
    public function __get(string $name)
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
    public function __unset(string $name)
    {
        unset($this->container[$name]);
    }
    
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $name
     */
    public function __isset(string $name): bool
    {
        return isset($this->container[$name]);
    }
}

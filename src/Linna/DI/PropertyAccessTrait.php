<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\DI;

/**
 * Magic Access Trait
 * Provide to DIContainer possibility to retrive values using properties
 * 
 */
trait PropertyAccessTrait
{
    /**
     * Set
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $id
     * @param mixed $value
     */
    public function __set(string $id, callable $value)
    {
        $this->set($id, $value);
    }
    
    /**
     * Get
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $id
     * @return object|bool Element stored in container or false
     */
    public function __get(string $id)
    {
        return $this->get($id);
    }
    
    /**
     * Remove
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $id
     */
    public function __unset(string $id) : bool
    {
        return $this->delete($id);
    }
    
    /**
     * Check
     * http://php.net/manual/en/language.oop5.overloading.php
     *
     * @param string $id
     */
    public function __isset(string $id) : bool
    {
        return $this->has($id);
    }
}
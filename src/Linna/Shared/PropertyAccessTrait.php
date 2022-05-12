<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Shared;

/**
 * Magic Access Trait
 * Provide to the possibility to retrive values using properties.
 */
trait PropertyAccessTrait
{
    use AbstractAccessTrait;

    /**
     * Set
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     *
     * @ignore
     */
    public function __set(string $key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Get
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $key
     *
     * @return object|bool Element stored in container or false
     *
     * @ignore
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * Remove
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $key
     *
     * @ignore
     */
    public function __unset(string $key)
    {
        $this->delete($key);
    }

    /**
     * Check
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $key
     *
     * @return bool
     *
     * @ignore
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }
}

<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Session;

/**
 * Magic Access Trait
 * Provide to Session the possibility to retrive values using properties.
 *
 * @property mixed $data Session Data
 */
trait PropertyAccessTrait
{
    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $name
     */
    public function __get(string $name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return false;
    }

    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $name
     */
    public function __unset(string $name)
    {
        unset($this->data[$name]);
    }

    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $name
     */
    public function __isset(string $name)
    {
        return isset($this->data[$name]);
    }
}

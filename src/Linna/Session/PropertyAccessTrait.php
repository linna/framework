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
     * @param string $offset
     * @param mixed  $value
     *
     * @return void
     */
    public function __set(string $offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function __get(string $offset)
    {
        if (isset($this->data[$offset])) {
            return $this->data[$offset];
        }

        return false;
    }

    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $offset
     */
    public function __unset(string $offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Magic metod
     * http://php.net/manual/en/language.oop5.overloading.php.
     *
     * @param string $offset
     */
    public function __isset(string $offset)
    {
        return isset($this->data[$offset]);
    }
}

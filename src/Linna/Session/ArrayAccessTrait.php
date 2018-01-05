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
 * Array Access Trait
 * Provide to Session the possibility to retrive values using array notation.
 *
 * @property mixed $data Session Data
 */
trait ArrayAccessTrait
{
    /**
     * Check.
     *
     * @param string $name
     *
     * @return bool
     */
    public function offsetExists($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Get.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function offsetGet($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return false;
    }

    /**
     * Store.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function offsetSet($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Delete.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function offsetUnset($name)
    {
        unset($this->data[$name]);
    }
}

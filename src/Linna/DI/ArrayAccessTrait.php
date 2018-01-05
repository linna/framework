<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DI;

/**
 * Array Access Trait
 * Provide to DIContainer the possibility to retrive values using array notation.
 */
trait ArrayAccessTrait
{
    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key
     */
    abstract public function has($key);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key
     */
    abstract public function get($key);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key
     * @param mixed  $value
     */
    abstract public function set($key, $value);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key
     */
    abstract public function delete($key);

    /**
     * Check.
     *
     * @param string $key
     *
     * @return bool
     *
     * @ignore
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @ignore
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Store.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @ignore
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Delete.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @ignore
     */
    public function offsetUnset($key)
    {
        return $this->delete($key);
    }
}

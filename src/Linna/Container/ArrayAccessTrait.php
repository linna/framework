<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Container;

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
    abstract public function delete($key): bool;

    /**
     * Check.
     *
     * @param mixed $key
     *
     * @return bool
     *
     * @ignore
     */
    public function offsetExists(mixed $key): bool
    {
        return $this->has($key);
    }

    /**
     * Get.
     *
     * @param mixed $key
     *
     * @return mixed
     *
     * @ignore
     */
    public function offsetGet(mixed $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Store.
     *
     * @param mixed $key
     * @param mixed  $value
     *
     * @return void
     *
     * @ignore
     */
    public function offsetSet(mixed $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    /**
     * Delete.
     *
     * @param mixed $offset
     *
     * @return void
     *
     * @ignore
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->delete($offset);
    }
}

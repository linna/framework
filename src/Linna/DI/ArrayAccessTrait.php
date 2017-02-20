<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
     * @param string $id
     */
    abstract public function has($id);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id
     */
    abstract public function get($id);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id
     * @param mixed  $value
     */
    abstract public function set($id, $value);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id
     */
    abstract public function delete($id);

    /**
     * Check.
     *
     * @param string $id
     *
     * @return bool
     */
    public function offsetExists($id)
    {
        return $this->has($id);
    }

    /**
     * Get.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function offsetGet($id)
    {
        return $this->get($id);
    }

    /**
     * Store.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function offsetSet($id, $value)
    {
        $this->set($id, $value);
    }

    /**
     * Delete.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function offsetUnset($id)
    {
        return $this->delete($id);
    }
}

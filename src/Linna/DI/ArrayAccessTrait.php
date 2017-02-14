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
 * Provide to DIContainer possibility to retrive values using array notation.
 */
trait ArrayAccessTrait
{
    /**
     * Express Requirements by Abstract Methods.
     */
    abstract public function has();

    /**
     * Express Requirements by Abstract Methods.
     */
    abstract public function get();

    /**
     * Express Requirements by Abstract Methods.
     */
    abstract public function set();

    /**
     * Express Requirements by Abstract Methods.
     */
    abstract public function delete();

    /**
     * Check.
     *
     * @param type $id
     *
     * @return type
     */
    public function offsetExists($id)
    {
        return $this->has($id);
    }

    /**
     * Get.
     *
     * @param type $id
     *
     * @return type
     */
    public function offsetGet($id)
    {
        return $this->get($id);
    }

    /**
     * Store.
     *
     * @param type $id
     * @param type $value
     */
    public function offsetSet($id, $value)
    {
        $this->set($id, $value);
    }

    /**
     * Delete.
     *
     * @param type $id
     *
     * @return type
     */
    public function offsetUnset($id)
    {
        return $this->delete($id);
    }
}

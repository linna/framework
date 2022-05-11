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
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Get.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (isset($this->data[$offset])) {
            return $this->data[$offset];
        }

        return false;
    }

    /**
     * Store.
     *
     * @param mixed $offset
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * Delete.
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }
}

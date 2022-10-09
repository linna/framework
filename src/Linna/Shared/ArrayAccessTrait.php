<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Shared;

/**
 * Array Access Trait.
 *
 * <p>Grant the possibility, for classes that use it, to retrive values using array notation.</p>
 * <p>This trait provide the concrete implementatio for the <code>ArrayAccess</code></p>
 */
trait ArrayAccessTrait
{
    use AbstractAccessTrait;

    /**
     * Whether an offset exists.
     *
     * <p>Whether or not an offset exists.</p>
     * <p>This method is executed when using <code>isset()</code> or <code>empty()</code> on objects implementing
     * <code>ArrayAccess</code>.</p>
     * <p><b>Note</b>:</p>
     * <p>When using <code>empty()</code> <code>ArrayAccess::offsetGet()</code> will be called and checked if empty
     * only if <b>ArrayAccess::offsetExists()</b> returns <b><code>true</code></b>.</p>
     *
     * @param mixed $offset An offset to check for.
     *
     * @return bool Returns <b><code>true</code></b> on success or <b><code>false</code></b> on failure.</p>
     *              <p><b>Note</b>:</p><p>The return value will be casted to <code>bool</code> if non-boolean was
     *              returned.
     *
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @since PHP 5, PHP 7, PHP 8
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve.
     *
     * <p>Returns the value at specified offset.</p>
     * <p>This method is executed when checking if offset is <code>empty()</code>.</p>
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     *
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @since PHP 5, PHP 7, PHP 8
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Assign a value to the specified offset.
     *
     * <p>Assigns a value to the specified offset.</p>
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     *
     * @return void No value is returned.
     *
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @since PHP 5, PHP 7, PHP 8
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * Unset an offset.
     *
     * <p>Unsets an offset.</p>
     * <p><b>Note</b>:</p>
     * <p>This method will <i>not</i> be called when type-casting to (unset)</p>
     *
     * @param mixed $offset The offset to unset.
     *
     * @return void No value is returned.
     *
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @since PHP 5, PHP 7, PHP 8
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->delete($offset);
    }
}

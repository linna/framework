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
 * Abstract Access Trait.
 *
 * <p>Provide abstract method that a class that implements <code>ArrayAccessTrait</code> or
 * <code>PropertyAccessTrait</code> must declare.</p>
 */
trait AbstractAccessTrait
{
    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id The identifier of the entry to look for.
     *
     * @return bool True if the class has the property, false otherwise.
     */
    abstract public function has(string $id): bool;

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id The identifier of the entry to look for.
     *
     * @return mixed The entry for which we are looking for.
     */
    abstract public function get(string $id): mixed;

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id    The identifier for the value which will be stored.
     * @param mixed  $value The value which will be stored.
     *
     * @return void
     */
    abstract public function set(string $id, mixed $value): void;

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id The identifier for the entry which will be deleted.
     *
     * @return void
     */
    abstract public function delete(string $id): void;
}

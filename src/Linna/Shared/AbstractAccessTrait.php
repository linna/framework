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
 * Magic Access Trait
 * Provide to the possibility to retrive values using properties.
 */
trait AbstractAccessTrait
{
    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id The identifier of the entry to look for.
     *
     * @return bool
     */
    abstract public function has(string $id): bool;

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id The identifier of the entry to look for.
     */
    abstract public function get(string $id);

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

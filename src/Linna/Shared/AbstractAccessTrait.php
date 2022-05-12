<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

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
     * @param string $id
     *
     * @return bool
     */
    abstract public function has(string $id): bool;

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id
     */
    abstract public function get(string $id);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id
     * @param mixed  $value
     *
     * @return void
     */
    abstract public function set(string $id, mixed $value): void;

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $id
     *
     * @return void
     */
    abstract public function delete(string $id): void;
}

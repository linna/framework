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
 * Magic Access Trait.
 *
 * <p>Grant the possibility, for classes that use it, to retrive values using property notation.</p>
 */
trait PropertyAccessTrait
{
    use AbstractAccessTrait;

    /**
     * Set.
     *
     * <p>Is run when writing data to inaccessible (protected or private) or non-existing properties.</p>
     *
     * @param string $name  The name of the property which will be updated.
     * @param mixed  $value The new value for the property.
     *
     * @return void
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php
     *
     * @ignore
     */
    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }

    /**
     * Get.
     *
     * <p>Is utilized for reading data from inaccessible (protected or private) or non-existing properties. </p>
     *
     * @param string $name The name of the property for which this method has invoked, the property to retrieve.
     *
     * @return mixed The property value, if the property exists.
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php
     *
     * @ignore
     */
    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

    /**
     * Remove.
     *
     * <p>Is invoked when unset() is used on inaccessible (protected or private) or non-existing properties. </p>
     *
     * @param string $name The name of the property which will be updated.
     *
     * @return void
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php
     *
     * @ignore
     */
    public function __unset(string $name): void
    {
        $this->delete($name);
    }

    /**
     * Check.
     *
     * <p>is triggered by calling <code>isset()</code> or <code>empty()</code> on inaccessible (protected or private) or non-existing properties.</p>
     *
     * @param string $name The name of the property for which verify the existence.
     *
     * @return bool
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php
     *
     * @ignore
     */
    public function __isset(string $name): bool
    {
        return $this->has($name);
    }
}

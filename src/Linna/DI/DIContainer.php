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

use Psr\Container\ContainerInterface;
use Linna\DI\Exception\NotFoundException;

/**
 * Dependency Injection Container.
 */
class DIContainer implements ContainerInterface, \ArrayAccess
{
    use PropertyAccessTrait;
    use ArrayAccessTrait;

    /**
     * @var array Callbacks storage
     */
    private $cache;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->cache = [];
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (array_key_exists($id, $this->cache)) {

            //move to temp for call function
            $tmp = $this->cache[$id];

            //return function result
            return $tmp();
        }

        throw new NotFoundException('No entry was found for this identifier');
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->cache[$id]);
    }

    /**
     * Store value inside container.
     *
     * @param string   $id
     * @param callable $value
     */
    public function set(string $id, callable $value)
    {
        $this->cache[$id] = $value;
    }

    /**
     * Delete value from container.
     *
     * @param string $id
     */
    public function delete(string $id) : bool
    {
        if (array_key_exists($id, $this->cache)) {

            //delete value
            unset($this->cache[$id]);

            //return function result
            return true;
        }

        return false;
    }
}

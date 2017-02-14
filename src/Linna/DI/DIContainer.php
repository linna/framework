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

use Interop\Container\ContainerInterface;
use Linna\DI\Exception\NotFound;

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
     * Get values.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFound No entry was found for this identifier.
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

        throw new NotFound('No entry was found for this identifier');
    }

    /**
     * Check if value is stored inside container.
     *
     * @param string $id Value identifier
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

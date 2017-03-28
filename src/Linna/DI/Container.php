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

use Linna\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Dependency Injection Container.
 */
class Container implements ContainerInterface, \ArrayAccess
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
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (isset($this->cache[$id])) {

            //move to temp for call function
            $tmp = $this->cache[$id];

            //return function result
            return $tmp();
        }

        throw new NotFoundException('No entry was found for this identifier');
    }

    /**
     * {@inheritdoc}
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

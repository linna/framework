<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Cache;

use DateInterval;
use DateTime;
use Linna\Cache\Exception\InvalidArgumentException;
use Traversable;

/**
 * ActionMultipleTrait.
 */
trait ActionMultipleTrait
{
    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     */
    abstract public function get($key, $default = null);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string                $key   The key of the item to store.
     * @param mixed                 $value The value of the item to store, must be serializable.
     * @param null|int|DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                     the driver supports TTL then the library may set a default value
     *                                     for it or let the driver take care of that.
     */
    abstract public function set($key, $value, $ttl);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key The cache item key.
     */
    abstract public function has($key);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key The unique cache key of the item to delete.
     */
    abstract public function delete($key);

    /**
     * {@inheritdoc}
     */
    public function getMultiple($keys, $default = null)
    {
        if (!is_array($keys) && !($keys instanceof Traversable)) {
            throw new InvalidArgumentException();
        }

        $result = [];
        foreach ((array) $keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setMultiple($values, $ttl = null)
    {
        if (!is_array($values) && !($values instanceof Traversable)) {
            throw new InvalidArgumentException();
        }

        foreach ((array) $values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMultiple($keys)
    {
        if (!is_array($keys) && !($keys instanceof Traversable)) {
            throw new InvalidArgumentException();
        }

        foreach ((array) $keys as $key) {
            $this->delete($key);
        }

        return true;
    }
}

<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Cache;

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
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    abstract public function get(string $key, $default = null);

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $value The value of the item to store, must be serializable.
     * @param int    $ttl   Optional. The TTL (time to live) value in seconds of this item.
     *                      If no value is sent and the driver supports TTL then the
     *                      library may set a default value for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     */
    abstract public function set(string $key, $value, int $ttl = 0): bool;

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key The cache item key.
     */
    abstract public function has(string $key): bool;

    /**
     * Express Requirements by Abstract Methods.
     *
     * @param string $key The unique cache key of the item to delete.
     */
    abstract public function delete(string $key): bool;

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param array<mixed> $keys    A list of keys that can obtained in a single operation.
     * @param mixed        $default Default value to return for keys that do not exist.
     *
     * @return array<mixed> A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     */
    public function getMultiple(array $keys, $default = null): array
    {
        $arrayResult = [];

        foreach ($keys as $key) {
            $arrayResult[$key] = $this->get($key, $default);
        }

        return $arrayResult;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param array<mixed> $values A list of key => value pairs for a multiple-set operation.
     * @param int          $ttl    Optional. The TTL (time to live) value in seconds of this item.
     *                             If no value is sent and the driver supports TTL then the
     *                             library may set a default value for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     */
    public function setMultiple(array $values, int $ttl = 0): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param array<mixed> $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     */
    public function deleteMultiple(array $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }
}

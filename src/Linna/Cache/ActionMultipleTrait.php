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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function setMultiple(array $values, int $ttl = 0): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMultiple(array $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }
}

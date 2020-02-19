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

use Memcached;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

/**
 * PSR-16 Memcached.
 */
class MemcachedCache implements CacheInterface
{
    use ActionMultipleTrait;

    /**
     * @var Memcached Memcached instance
     */
    private Memcached $memcached;

    /**
     * Constructor.
     *
     * @param array $options
     *
     * @throws InvalidArgumentException if options not contain memcached resource
     */
    public function __construct(array $options)
    {
        if (!($options['resource'] instanceof Memcached)) {
            throw new InvalidArgumentException('MemcachedCache class need instance of Memcached passed as option. [\'resource\' => $memcached].');
        }

        $this->memcached = $options['resource'];
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    public function get(string $key, $default = null)
    {
        //get value from memcached
        $value = $this->memcached->get($key);

        //check if value was retrived
        if ($value === false) {
            return $default;
        }

        return $value;
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $value The value of the item to store, must be serializable.
     * @param int    $ttl   Optional. The TTL (time to live) value in seconds of this item.
     *                      If no value is sent and the driver supports TTL then the
     *                      library may set a default value for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        return $this->memcached->set($key, $value, $ttl);
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     */
    public function delete(string $key): bool
    {
        return $this->memcached->delete($key);
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool
    {
        return $this->memcached->flush();
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it making the state of your app out of date.
     *
     * @param string $key The cache item key.
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return ($this->memcached->get($key) !== false) ? true : false;
    }
}

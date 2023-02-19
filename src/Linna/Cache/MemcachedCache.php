<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Cache;

use DateInterval;
use Memcached;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

/**
 * PSR-16 Memcached.
 */
class MemcachedCache implements CacheInterface
{
    use ActionMultipleTrait;
    use TtlTrait;

    /** @var Memcached Memcached instance */
    private Memcached $memcached;

    /**
     * Class Constructor.
     *
     * @param array<mixed> $options Options for memecached, passing at least parameters for one server is mandatory, ex.
     *                              <code>MemcachedCache(['host' => 'mem1.domain.com', 'port' => 11211])</code> or
     *                              <code>MemcachedCache(['servers' => [['mem1.domain.com', 11211], ['mem2.domain.com', 11211]]])</code>.
     *                              The array of options should contains same parameters as required
     *                              from <code>Memcached::addServers</code> or <code>Memcached::addServers</code>.
     *
     * @throws InvalidArgumentException if with options is not possible configure memecached servers.
     *
     * @link https://www.php.net/manual/en/memcached.addserver.php
     * @link https://www.php.net/manual/en/memcached.addservers.php
     */
    public function __construct(array $options)
    {
        $this->memcached = new Memcached();

        $result = false;

        if (isset($options['host']) && isset($options['port'])) {
            $weight = $options['weight'] ?? 0;
            $result |= $this->memcached->addServer(
                host: $options['host'],
                port: $options['port'],
                weight: $weight
            );
        }

        if (isset($options['servers'])) {
            $result |= $this->memcached->addServers(servers: $options['servers']);
        }

        if (!$result) {
            throw new \InvalidArgumentException('Somethig went worng adding memcached servers.');
        }
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or <code>$default</code> in case of cache miss.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the <code>$key</code> string is not a legal
     *                                                   value.
     */
    public function get(string $key, mixed $default = null): mixed
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
     * @param string                 $key   The key of the item to store.
     * @param mixed                  $value The value of the item to store, must be serializable.
     * @param null|int|\DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the <code>$key</code> string is not a legal
     *                                                   value.
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $handledTtl = $this->handleTtl($ttl);

        //set key with ttl
        if ($handledTtl > 0) {
            return $this->memcached->set($key, $value, $handledTtl);
        }

        //ttl negative try to remove existing key
        if ($handledTtl < 0) {
            $this->memcached->delete($key);
            return true;
        }

        //set key that does not expire
        return $this->memcached->set($key, $value);
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the <code>$key</code> string is not a legal
     *                                                   value.
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
     * <p><p><b>Note</b>:</p> It is recommended that <code>has()</code> is only to be used for cache warming type
     * purposes and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your <code>has()</code> will return true and immediately after,
     * another script can remove it making the state of your app out of date.</p>
     *
     * @param string $key The cache item key.
     *
     * @return bool
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the <code>$key</code> string is not a legal
     *                                                   value.
     */
    public function has(string $key): bool
    {
        return ($this->memcached->get($key) !== false) ? true : false;
    }
}

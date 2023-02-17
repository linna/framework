<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2023, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Cache;

use DateInterval;
use Redis;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

/**
 * PSR-16 Redis.
 */
class RedisCache implements CacheInterface
{
    use ActionMultipleTrait;

    /** @var Redis Redis client instance */
    private Redis $redis;

    /**
     * Class Constructor.
     *
     * @param array<mixed> $options 
     *
     * @throws InvalidArgumentException if with options is not possible configure memecached servers.
     *
     * @link https://www.php.net/manual/en/memcached.addserver.php
     * @link https://www.php.net/manual/en/memcached.addservers.php
     */
    public function __construct(array $options)
    {
        $this->redis = new Redis();

        $isHost          = isset($options['host']);
        $isPort          = isset($options['port']);
        $isTimeout       = isset($options['timeout']);
        $isReserved      = isset($options['reserver']);
        $isRetryInterval = isset($options['retry_interval']);
        $isReadTimeout   = isset($options['read_timeout']);
        $isOthers        = isset($options['others']);
        
        
        if ($isHost && $isPort && $isTimeout) {
            $this->redis->connect(
                host: $options['host'],
                port: $options['port'],
                timeout: $options['timeout']);
        }
        else if ($isHost && $isPort) {
            $this->redis->connect(
                host: $options['host'],
                port: $options['port']);
        }
        else {
            throw new \InvalidArgumentException('Somethig went worng creating Redis client.');
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

    }

    /**
     * Handle TTL parameter.
     *
     * @param null|int|\DateInterval $ttl Optional. The TTL value of this item. If no value is sent and
     *                                    the driver supports TTL then the library may set a default value
     *                                    for it or let the driver take care of that.
     *
     * @return int TTL in seconds.
     */
    private function handleTtl(DateInterval|int|null $ttl): int
    {
        if ($ttl === null) {
            return 0;
        }
        if (\is_int($ttl)) {
            return $ttl;
        }
        if ($ttl instanceof DateInterval) {
            $now = new \DateTime();
            $now->add($ttl);
            return (int) $now->format('U');
        }
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

    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool
    {

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

    }
}

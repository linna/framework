<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2023, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Cache;

use DateInterval;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;
use Redis;

/**
 * PSR-16 Redis.
 */
class RedisCache implements CacheInterface
{
    use ActionMultipleTrait;
    use TtlTrait;

    /** @var Redis Redis client instance */
    private Redis $redis;

    /**
     * Class Constructor.
     *
     * @param array<mixed> $options Connection Options, as explained in link.
     *
     * @link https://github.com/phpredis/phpredis#connection
     */
    public function __construct(array $options)
    {
        $this->redis = new Redis();
        $callback = [$this->redis, 'connect'];

        if (!isset($options['connect']['host']) || \is_callable($callback) && !\call_user_func_array($callback, $options['connect'])) {
            throw new InvalidArgumentException('Unable to connect to Redis server.');
        }

        if (isset($options['auth']) && !$this->redis->auth($options['auth'])) {
            throw new InvalidArgumentException('Unable to authenticate to Redis server.');
        }
    }

    /**
     * Class Desctructor.
     *
     * Close Redis connection.
     */
    public function __destruct()
    {
        $this->redis->close();
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
        if (($value = $this->redis->get($key)) !== false) {
            return \unserialize($value);
        }

        return $default;
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                $key   The key of the item to store.
     * @param mixed                 $value The value of the item to store, must be serializable.
     * @param DateInterval|int|null $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                     the driver supports TTL then the library may set a default value
     *                                     for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the <code>$key</code> string is not a legal
     *                                                   value.
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $handledTtl = $this->handleTtl($ttl);
        $serialized = \serialize($value);

        //set key with ttl
        if ($handledTtl > 0) {
            return $this->redis->setex($key, $handledTtl, $serialized);
        }

        //ttl negative try to remove existing key
        if ($handledTtl < 0) {
            $this->redis->del($key);
            return true;
        }

        //set key that does not expire
        return $this->redis->set($key, $serialized);
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
        $keyDeleted = $this->redis->del($key);

        if ($keyDeleted === 1) {
            return true;
        }

        return false;
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool
    {
        return $this->redis->flushDb();
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
        if ($this->redis->exists($key) > 0) {
            return true;
        }

        return false;
    }
}

<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Cache;

use DateInterval;

/**
 * ActionMultipleTrait.
 */
trait ActionMultipleTrait
{
    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if the $key string is not a legal value.
     */
    abstract public function get(string $key, mixed $default = null): mixed;

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                $key   The key of the item to store.
     * @param mixed                 $value The value of the item to store, must be serializable.
     * @param null|int|DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                     the driver supports TTL then the library may set a default value
     *                                     for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the <code>$key</code> string is not a
     *                                                   legal value.
     */
    abstract public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool;

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
    abstract public function has(string $key): bool;

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
    abstract public function delete(string $key): bool;

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable<string> $keys    A list of keys that can be obtained in a single operation.
     * @param mixed            $default Default value to return for keys that do not exist.
     *
     * @return iterable<string, mixed> A list of <code>key => value</code> pairs. Cache keys that do not exist or are
     *                                 stale will have <code>$default</code> as value.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if <code>$keys</code> is neither an array nor a
     *                                                   Traversable, or if any of the <code>$keys</code> are not a
     *                                                   legal value.
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $arrayResult = [];

        foreach ($keys as $key) {
            $arrayResult[$key] = $this->get($key, $default);
        }

        return $arrayResult;
    }

    /**
     * Persists a set of [key => value] pairs in the cache, with an optional TTL.
     *
     * @param iterable<string, mixed> $values A list of <code>key => value</code> pairs for a multiple-set operation.
     * @param DateInterval|int|null   $ttl    Optional. The TTL value of this item. If no value is sent and the driver
     *                                        supports TTL then the library may set a default value for it or let the
     *                                        driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if <code>$values</code> is neither an array nor
     *                                                   a Traversable, or if any of the <code>$values</code> are not a
     *                                                   legal value.
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable<string> $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if <code>$keys</code> is neither an array nor
     *                                                   a <code>Traversable</code>, or if any of the
     *                                                   <code>$keys</code> are not a legal value.
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }
}

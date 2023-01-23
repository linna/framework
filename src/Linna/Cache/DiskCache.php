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
use Psr\SimpleCache\CacheInterface;

/**
 * PSR-16 Disk Cache.
 *
 * <p>Before use it, is possible configure ramdisk, work only on linux:</p>
 * <pre>
 * mkdir /tmp/linna-cache
 * sudo mount -t tmpfs -o size=128m tmpfs /tmp/linna-cache
 * </pre>
 *
 * <p>To check Ram Disk status</p>
 * <pre>
 * df -h /tmp/linna-cache
 * </pre>
 *
 * <p>Serialize option is required when is needed to store a class instance.
 * If you don't utilize serialize, have to declare <code>__set_state()</code> method inside
 * class or get from cache will fail.</p>
 */
class DiskCache implements CacheInterface
{
    use ActionMultipleTrait;

    /** @var string Directory for cache storage. */
    protected string $dir = '/tmp';

    /**
     * Class Constructor.
     *
     * @param array<mixed> $options
     */
    public function __construct(array $options = [])
    {
        ['dir' => $this->dir] = \array_replace_recursive(['dir' => '/tmp'], $options);
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
        //create file name
        $file = $this->dir.'/'.\sha1($key).'.php';

        if ($this->doesFileCheckFails($file)) {
            return $default;
        }

        $cache = include $file;

        return \unserialize($cache['value']);
    }

    /**
     * Checks for cache file presence and validity.
     *
     * @param string $file The file name which will be checked.
     *
     * @return bool True if all checks fail, false otherwise.
     */
    private function doesFileCheckFails(string $file): bool
    {
        //check if file exist
        if (!\file_exists($file)) {
            return true;
        }

        //take cache from file
        $cacheValue = include $file;

        //check if cache is expired and delete file from storage
        if ($cacheValue['expires'] <= \time() && $cacheValue['expires'] !== 0) {
            \unlink($file);

            return true;
        }

        return false;
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
        $vTtl = $this->handleTtl($ttl);

        //create cache array
        $cache = [
            'key'     => $key,
            'value'   => \serialize($value),
            'expires' => $this->calculateTtl($vTtl),
        ];

        //export
        // HHVM fails at __set_state, so just use object cast for now
        $content = \str_replace('stdClass::__set_state', '(object)', \var_export($cache, true));
        $content = "<?php return {$content};";

        //write file
        \file_put_contents($this->dir.'/'.\sha1($key).'.php', $content);

        return true;
    }

    /**
     * Handle ttl parameter.
     *
     * @param null|int|\DateInterval $ttl Optional. The TTL value of this item. If no value is sent and
     *                                    the driver supports TTL then the library may set a default value
     *                                    for it or let the driver take care of that.
     *
     * @return int Ttl in seconds.
     */
    private function handleTtl(DateInterval|int|null $ttl): int
    {
        if ($ttl == null) {
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
     * Calculate ttl for cache file.
     *
     * @param int $ttl The TTL
     *
     * @return int The TTL updated if the passed value is grather than zero.
     */
    private function calculateTtl(int $ttl): int
    {
        //check for usage of ttl default class option value
        if ($ttl) {
            return \time() + $ttl;
        }

        return $ttl;
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
        //create file name
        $file = $this->dir.'/'.\sha1($key).'.php';

        //chek if file exist and delete
        if (\file_exists($file)) {
            \unlink($file);

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
        // iterate every cache file and delete it.
        foreach ((array) \glob($this->dir.'/*.php') as $file) {
            if ($file !== false && \unlink($file) !== false) {
                continue;
            }
            // something went wrong with file deleting.
            return false;
        }

        return true;
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
        return !$this->doesFileCheckFails($this->dir.'/'.\sha1($key).'.php');
    }
}

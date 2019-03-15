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

use Psr\SimpleCache\CacheInterface;

/**
 * PSR-16 Disk Cache.
 *
 * Before use it, is possible configure ramdisk, work only on linux:
 * - mkdir /tmp/linna-cache
 * - sudo mount -t tmpfs -o size=128m tmpfs /tmp/linna-cache
 *
 * For check Ram Disk status
 * - df -h /tmp/linna-cache
 *
 * Serialize option is required when is needed store a class instance.
 * If you not utilize serialize, must declare __set_state() method inside
 * class or get from cache fail.
 */
class DiskCache implements CacheInterface
{
    use ActionMultipleTrait;

    /**
     * @var string Directory for cache storage.
     */
    protected $dir = '/tmp';

    /**
     * Constructor.
     *
     * @param array $options
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
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    public function get(string $key, $default = null)
    {
        //create file name
        $file = $this->dir.'/'.\sha1($key).'.php';

        if ($this->doesFileChecksFailed($file)) {
            return $default;
        }

        $cacheValue = include $file;

        return \unserialize($cacheValue['value']);
    }

    /**
     * Checks for cache file.
     *
     * @param string $file
     *
     * @return bool
     */
    private function doesFileChecksFailed(string $file): bool
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
        //create cache array
        $cache = [
            'key'     => $key,
            'value'   => \serialize($value),
            'expires' => $this->calculateTtl($ttl),
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
     * Calculate ttl for cache file.
     *
     * @param int $ttl
     *
     * @return int
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
        \array_map('unlink', \glob($this->dir.'/*.php'));

        return true;
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
        return !$this->doesFileChecksFailed($this->dir.'/'.\sha1($key).'.php');
    }
}

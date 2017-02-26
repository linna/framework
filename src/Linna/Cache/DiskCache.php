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
use Linna\Cache\Exception\InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;
use Traversable;

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
    /**
     * @var array Config options for class
     */
    protected $options = [
        'dir'       => '/tmp',
        'serialize' => false,
        'ttl'       => null,
    ];

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        //set options
        $this->options = array_replace_recursive($this->options, $options);
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if the $key string is not a legal value.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    public function get($key, $default = null)
    {
        //check if key is string
        if (!is_string($key)) {
            throw new InvalidArgumentException();
        }

        //create file name
        $file = $this->options['dir'].'/'.sha1($key).'.php';

        //check if file exist
        if (!file_exists($file)) {
            return $default;
        }

        //take cache from file
        $cacheValue = include $file;

        //check if cache is expired and delete file from storage
        if ($cacheValue['expires'] <= time() && $cacheValue['expires'] !== null) {
            unlink($file);

            return $default;
        }

        //return cache, unserialize if serialize options set to true
        return isset($cacheValue['value']) ? ($this->options['serialize']) ? unserialize($cacheValue['value']) : $cacheValue['value'] : $default;
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                $key   The key of the item to store.
     * @param mixed                 $value The value of the item to store, must be serializable.
     * @param null|int|DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                     the driver supports TTL then the library may set a default value
     *                                     for it or let the driver take care of that.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if the $key string is not a legal value.
     *
     * @return bool True on success and false on failure.
     */
    public function set($key, $value, $ttl = null)
    {
        //pick time
        $created = time();

        if ($ttl instanceof DateInterval) {
            // Converting to a TTL in seconds
            $ttl = (new DateTime('now'))->add($ttl)->getTimeStamp() - $created;
        }

        //check for usage of ttl default class option value
        $ttl = ($ttl === null) ? $this->options['ttl'] : $ttl;

        //create file name
        $file = $this->options['dir'].'/'.sha1($key).'.php';

        //check for serialize and do if set to true
        $value = ($this->options['serialize']) ? serialize($value) : $value;

        //create cache array
        $cache = [
            'created' => $created,
            'key'     => $key,
            'value'   => $value,
            'ttl'     => $ttl,
            'expires' => ($ttl) ? $created + $ttl : null,
        ];

        //export
        $content = var_export($cache, true);
        // HHVM fails at __set_state, so just use object cast for now
        $content = str_replace('stdClass::__set_state', '(object)', $content);
        $content = '<?php return '.$content.';';

        //write file
        file_put_contents($file, $content);

        return true;
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if the $key string is not a legal value.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     */
    public function delete($key)
    {
        //check if key is string
        if (!is_string($key)) {
            throw new InvalidArgumentException();
        }

        //create file name
        $file = $this->options['dir'].'/'.sha1($key).'.php';

        //chek if file exist and delete
        if (file_exists($file)) {
            unlink($file);

            return true;
        }

        return false;
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear()
    {
        array_map('unlink', glob($this->options['dir'].'/*.*'));

        return true;
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if $keys is neither an array nor a Traversable,
     *                                                   or if any of the $keys are not a legal value.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     */
    public function getMultiple($keys, $default = null)
    {
        if (!is_array($keys) && !($keys instanceof Traversable)) {
            throw new InvalidArgumentException();
        }

        $result = [];
        foreach ((array) $keys as $key) {
            $result[$key] = $this->has($key) ? $this->get($key) : $default;
        }

        return $result;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable              $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if $values is neither an array nor a Traversable,
     *                                                   or if any of the $values are not a legal value.
     *
     * @return bool True on success and false on failure.
     */
    public function setMultiple($values, $ttl = null)
    {
        if (!is_array($values) && !($values instanceof Traversable)) {
            throw new InvalidArgumentException();
        }

        if ($ttl instanceof DateInterval) {
            // Converting to a TTL in seconds
            $ttl = (new DateTime('now'))->add($ttl)->getTimeStamp() - time();
        }

        foreach ((array) $values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if $keys is neither an array nor a Traversable,
     *                                                   or if any of the $keys are not a legal value.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
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
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if the $key string is not a legal value.
     *
     * @return bool
     */
    public function has($key)
    {
        //check if key is string
        if (!is_string($key)) {
            throw new InvalidArgumentException();
        }

        //create file name
        $file = $this->options['dir'].'/'.sha1($key).'.php';

        //check if file exist
        if (!file_exists($file)) {
            return false;
        }

        //take cache from file
        $cacheValue = include $file;

        //check if cache is expired and delete file from storage
        if ($cacheValue['expires'] <= time() && $cacheValue['expires'] !== null) {
            unlink($file);

            return false;
        }

        return true;
    }
}

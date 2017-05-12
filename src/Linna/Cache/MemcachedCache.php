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

use Memcached;
use Psr\SimpleCache\CacheInterface;

/**
 * PSR-16 Memcached.
 */
class MemcachedCache implements CacheInterface
{
    use ActionMultipleTrait;

    /**
     * @var object Memcached resource
     */
    private $memcached;

    /**
     * Constructor.
     *
     * @param Memcached $memcached
     */
    public function __construct(Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function set(string $key, $value, int $ttl = 0) : bool
    {
        return $this->memcached->set($key, $value, (int) $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $key) : bool
    {
        return $this->memcached->delete($key);
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear() : bool
    {
        return $this->memcached->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key) : bool
    {
        return ($this->memcached->get($key) !== false) ? true : false;
    }
}

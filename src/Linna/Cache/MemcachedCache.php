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
    private $memcached;

    /**
     * Constructor.
     *
     * @param array $options
     * @throws InvalidArgumentException if options not contain memcached resource
     */
    public function __construct(array $options)
    {
        if (!($options['resource'] instanceof Memcached)) {
            throw new InvalidArgumentException(__class__.' need instance of Memcached passed as option. [\'resource\' => $memcached]');
        }
        
        $this->memcached = $options['resource'];
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
        return $this->memcached->set($key, $value, $ttl);
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

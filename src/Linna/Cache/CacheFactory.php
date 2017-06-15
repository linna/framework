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

use Psr\SimpleCache\CacheInterface;
use InvalidArgumentException;

/**
 * Storage Factory.
 */
class CacheFactory
{
    /**
     * @var string One of supported drivers
     */
    private $driver;

    /**
     * @var array Factory supported driver
     */
    private $supportedDriver = [
        'disk'       => DiskCache::class,
        'memcached'  => MemcachedCache::class,
    ];

    /**
     * @var array Options for the driver
     */
    private $options;

    /**
     * Constructor.
     *
     * @param string $driver
     * @param array  $options
     */
    public function __construct(string $driver, array $options)
    {
        $this->driver = $driver;
        $this->options = $options;
    }

    /**
     * Return Cache Resource.
     *
     * @throws InvalidArgumentException If required driver is not supported
     *
     * @return CacheInterface
     */
    public function get() : CacheInterface
    {
        $driver = $this->driver;
        $options = $this->options;

        if (isset($this->supportedDriver[$driver])) {
            $cache = $this->supportedDriver[$driver];

            return new $cache($options);
        }

        throw new InvalidArgumentException("[$driver] not supported.");
    }
}
